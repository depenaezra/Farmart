<?php

namespace App\Controllers;

use App\Models\ForumPostModel;
use App\Models\ForumCommentModel;

class Forum extends BaseController
{
    protected $forumPostModel;
    protected $forumCommentModel;
    
    public function __construct()
    {
        $this->forumPostModel = new \CodeIgniter\Model();
        $this->forumCommentModel = new \CodeIgniter\Model();
        
        // Simple models for forum
        $this->forumPostModel->setTable('forum_posts');
        $this->forumCommentModel->setTable('forum_comments');
    }
    
    /**
     * Forum index - list all posts
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Create forum_likes table if it doesn't exist
        $this->createForumLikesTable($db);
        
        // Get filter and sort parameters
        $category = $this->request->getGet('category');
        $sort = $this->request->getGet('sort') ?? 'latest';
        
        // Build query
        $query = $db->table('forum_posts')
                    ->select('forum_posts.*, users.name as author_name, 
                             (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.post_id = forum_posts.id) as comment_count,
                             (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.post_id = forum_posts.id) as likes_count')
                    ->join('users', 'users.id = forum_posts.user_id');
        
        // Apply category filter
        if ($category && $category !== 'all') {
            $query->where('forum_posts.category', $category);
        }
        
        // Apply sorting
        if ($sort === 'oldest') {
            $query->orderBy('forum_posts.created_at', 'ASC');
        } else {
            $query->orderBy('forum_posts.created_at', 'DESC');
        }
        
        $posts = $query->get()->getResultArray();
        
        // Get all unique categories for filter dropdown
        $categories = $db->table('forum_posts')
                        ->select('category')
                        ->distinct()
                        ->where('category IS NOT NULL')
                        ->where('category !=', '')
                        ->orderBy('category', 'ASC')
                        ->get()
                        ->getResultArray();
        
        // Check if current user has liked each post and get recent comments
        if ($userId) {
            foreach ($posts as &$post) {
                $post['user_liked'] = $db->table('forum_likes')
                    ->where('post_id', $post['id'])
                    ->where('user_id', $userId)
                    ->countAllResults() > 0;
                $post['likes'] = $post['likes_count'] ?? 0;

                // Get recent comments (first 5, ordered by oldest first)
                $post['comments'] = $db->table('forum_comments')
                    ->select('forum_comments.*, users.name as author_name, users.role as author_role')
                    ->join('users', 'users.id = forum_comments.user_id')
                    ->where('forum_comments.post_id', $post['id'])
                    ->orderBy('forum_comments.created_at', 'ASC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();

                // Get total comment count
                $post['total_comments'] = $db->table('forum_comments')
                    ->where('post_id', $post['id'])
                    ->countAllResults();
            }
        } else {
            foreach ($posts as &$post) {
                $post['user_liked'] = false;
                $post['likes'] = $post['likes_count'] ?? 0;

                // Get recent comments (first 5, ordered by oldest first)
                $post['comments'] = $db->table('forum_comments')
                    ->select('forum_comments.*, users.name as author_name, users.role as author_role')
                    ->join('users', 'users.id = forum_comments.user_id')
                    ->where('forum_comments.post_id', $post['id'])
                    ->orderBy('forum_comments.created_at', 'ASC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();

                // Get total comment count
                $post['total_comments'] = $db->table('forum_comments')
                    ->where('post_id', $post['id'])
                    ->countAllResults();
            }
        }
        
        $data = [
            'title' => 'Community Forum',
            'posts' => $posts,
            'categories' => array_column($categories, 'category'),
            'selected_category' => $category ?? 'all',
            'selected_sort' => $sort
        ];
        
        return view('forum/index', $data);
    }
    
    /**
     * View single post with comments
     */
    public function viewPost($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Create forum_likes table if it doesn't exist
        $this->createForumLikesTable($db);
        
        $post = $db->table('forum_posts')
                   ->select('forum_posts.*, users.name as author_name, users.role as author_role,
                            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.post_id = forum_posts.id) as likes_count')
                   ->join('users', 'users.id = forum_posts.user_id')
                   ->where('forum_posts.id', $id)
                   ->get()
                   ->getRowArray();
        
        if (!$post) {
            return redirect()->to('/forum')
                ->with('error', 'Post not found.');
        }
        
        // Check if current user has liked this post
        if ($userId) {
            $post['user_liked'] = $db->table('forum_likes')
                ->where('post_id', $id)
                ->where('user_id', $userId)
                ->countAllResults() > 0;
        } else {
            $post['user_liked'] = false;
        }
        $post['likes'] = $post['likes_count'] ?? 0;
        
        $comments = $db->table('forum_comments')
                       ->select('forum_comments.*, users.name as author_name, users.role as author_role')
                       ->join('users', 'users.id = forum_comments.user_id')
                       ->where('forum_comments.post_id', $id)
                       ->orderBy('forum_comments.created_at', 'ASC')
                       ->get()
                       ->getResultArray();
        
        $data = [
            'title' => $post['title'],
            'post' => $post,
            'comments' => $comments
        ];
        
        return view('forum/view_post', $data);
    }
    
    /**
     * Create new post form
     */
    public function create()
    {
        $editId = $this->request->getGet('edit');
        $data = [
            'title' => 'Create New Post',
            'edit_post' => null
        ];

        // Handle edit mode
        if ($editId) {
            $db = \Config\Database::connect();
            $post = $db->table('forum_posts')->where('id', $editId)->get()->getRowArray();

            // Check if post exists and user owns it
            if ($post && $post['user_id'] == session()->get('user_id')) {
                $data['title'] = 'Edit Post';
                $data['edit_post'] = $post;
            } else {
                return redirect()->to('/forum')
                    ->with('error', 'Post not found or you do not have permission to edit it.');
            }
        }

        return view('forum/create', $data);
    }
    
    /**
     * Process create/edit post
     */
    public function createProcess()
    {
        $editId = $this->request->getPost('edit_id');
        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'required|min_length[20]',
            'category' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();

        // Check if editing existing post
        if ($editId) {
            $existingPost = $db->table('forum_posts')->where('id', $editId)->get()->getRowArray();
            if (!$existingPost || $existingPost['user_id'] != session()->get('user_id')) {
                return redirect()->to('/forum')
                    ->with('error', 'Post not found or you do not have permission to edit it.');
            }
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category') ?? 'general',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Only set user_id and created_at for new posts
        if (!$editId) {
            $data['user_id'] = session()->get('user_id');
            $data['likes'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        // Handle optional multiple image uploads (field name: images[])
        $files = $this->request->getFiles();
        $imageUrls = [];

        if (isset($files['images'])) {
            $uploadPath = FCPATH . 'uploads/forum/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    // store relative path to use in views
                    $imageUrls[] = 'uploads/forum/' . $newName;
                }
            }
        }

        if (!empty($imageUrls)) {
            $data['image_url'] = json_encode($imageUrls);
        }

        if ($editId) {
            // Update existing post
            if ($db->table('forum_posts')->update($data, ['id' => $editId])) {
                // Delete existing mentions and re-extract
                $db->table('forum_mentions')->delete(['post_id' => $editId]);

                // Extract mentions like @username and populate forum_mentions
                $content = $data['content'] ?? '';
                if (preg_match_all('/@([A-Za-z0-9_\-\.]+)/', $content, $matches)) {
                    $usernames = array_unique($matches[1]);
                    foreach ($usernames as $uname) {
                        $u = $db->table('users')
                            ->select('id')
                            ->where('username', $uname)
                            ->orWhere('name', $uname)
                            ->get()
                            ->getRowArray();
                        if ($u && isset($u['id'])) {
                            try {
                                $db->table('forum_mentions')->insert([
                                    'post_id' => $editId,
                                    'mentioned_user_id' => $u['id'],
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            } catch (\Exception $e) {
                                // ignore duplicate/constraint errors
                            }
                        }
                    }
                }

                return redirect()->to('/forum')
                    ->with('success', 'Post updated successfully!');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to update post.');
            }
        } else {
            // Insert new post
            if ($db->table('forum_posts')->insert($data)) {
                $postId = $db->insertID();

                // Extract mentions like @username and populate forum_mentions
                $content = $data['content'] ?? '';
                if (preg_match_all('/@([A-Za-z0-9_\-\.]+)/', $content, $matches)) {
                    $usernames = array_unique($matches[1]);
                    foreach ($usernames as $uname) {
                        $u = $db->table('users')
                            ->select('id')
                            ->where('username', $uname)
                            ->orWhere('name', $uname)
                            ->get()
                            ->getRowArray();
                        if ($u && isset($u['id'])) {
                            try {
                                $db->table('forum_mentions')->insert([
                                    'post_id' => $postId,
                                    'mentioned_user_id' => $u['id'],
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            } catch (\Exception $e) {
                                // ignore duplicate/constraint errors
                            }
                        }
                    }
                }

                return redirect()->to('/forum')
                    ->with('success', 'Post created successfully!');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to create post.');
            }
        }
    }
    
    /**
     * Add comment to post
     */
    public function addComment($postId)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'comment' => 'required|min_length[1]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Comment cannot be empty.');
        }
        
        $db = \Config\Database::connect();
        
        $data = [
            'post_id' => $postId,
            'user_id' => session()->get('user_id'),
            'comment' => $this->request->getPost('comment'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('forum_comments')->insert($data)) {
            $insertId = $db->insertID();

            // If AJAX request, return comment data for client-side rendering
            if ($this->request->isAJAX()) {
                $comment = $db->table('forum_comments')
                    ->select('forum_comments.*, users.name as author_name, users.role as author_role, users.id as user_id')
                    ->join('users', 'users.id = forum_comments.user_id')
                    ->where('forum_comments.id', $insertId)
                    ->get()
                    ->getRowArray();

                $count = $db->table('forum_comments')->where('post_id', $postId)->countAllResults();

                return $this->response->setJSON([
                    'success' => true,
                    'comment' => $comment,
                    'comment_count' => $count
                ]);
            }

            return redirect()->back()
                ->with('success', 'Comment added!');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add comment.']);
            }

            return redirect()->back()
                ->with('error', 'Failed to add comment.');
        }
    }
    
    /**
     * Like/Unlike post (toggle)
     */
    public function likePost($postId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to like posts.');
        }
        
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Create forum_likes table if it doesn't exist
        $this->createForumLikesTable($db);
        
        // Check if post exists
        $post = $db->table('forum_posts')->where('id', $postId)->get()->getRowArray();
        if (!$post) {
            return redirect()->back()
                ->with('error', 'Post not found.');
        }
        
        // Check if user has already liked this post
        $existingLike = $db->table('forum_likes')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();
        
        if ($existingLike) {
            // Unlike - remove the like
            $db->table('forum_likes')
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->delete();
            
            return redirect()->back()
                ->with('success', 'Post unliked!');
        } else {
            // Like - add the like
            $db->table('forum_likes')->insert([
                'post_id' => $postId,
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            return redirect()->back()
                ->with('success', 'Post liked!');
        }
    }
    
    /**
     * Create forum_likes table if it doesn't exist
     */
    private function createForumLikesTable($db)
    {
        // Check if table exists
        $tables = $db->listTables();
        if (!in_array('forum_likes', $tables)) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'post_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey(['post_id', 'user_id']);
            $forge->addKey('user_id');
            $forge->createTable('forum_likes');
        }
    }
    
    /**
     * Load more comments for a post (AJAX)
     */
    public function loadMoreComments($postId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $db = \Config\Database::connect();
        $offset = (int) $this->request->getGet('offset') ?? 0;
        $limit = (int) $this->request->getGet('limit') ?? 10;

        // Check if post exists
        $post = $db->table('forum_posts')->where('id', $postId)->get()->getRowArray();
        if (!$post) {
            return $this->response->setJSON(['success' => false, 'message' => 'Post not found']);
        }

        $comments = $db->table('forum_comments')
            ->select('forum_comments.*, users.name as author_name, users.role as author_role')
            ->join('users', 'users.id = forum_comments.user_id')
            ->where('forum_comments.post_id', $postId)
            ->orderBy('forum_comments.created_at', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        $totalComments = $db->table('forum_comments')
            ->where('post_id', $postId)
            ->countAllResults();

        $hasMore = ($offset + count($comments)) < $totalComments;

        return $this->response->setJSON([
            'success' => true,
            'comments' => $comments,
            'has_more' => $hasMore,
            'total' => $totalComments
        ]);
    }

    /**
     * Delete post
     */
    public function deletePost($postId)
    {
        $db = \Config\Database::connect();

        $post = $db->table('forum_posts')->where('id', $postId)->get()->getRowArray();

        if (!$post) {
            return redirect()->to('/forum')
                ->with('error', 'Post not found.');
        }

        // Only post author or admin can delete
        if ($post['user_id'] != session()->get('user_id') && session()->get('user_role') != 'admin') {
            return redirect()->back()
                ->with('error', 'You do not have permission to delete this post.');
        }

        if ($db->table('forum_posts')->delete(['id' => $postId])) {
            return redirect()->to('/forum')
                ->with('success', 'Post deleted.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete post.');
        }
    }
}
