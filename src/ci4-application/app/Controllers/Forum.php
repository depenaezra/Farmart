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
        
        $posts = $db->table('forum_posts')
                    ->select('forum_posts.*, users.name as author_name, 
                             (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.post_id = forum_posts.id) as comment_count')
                    ->join('users', 'users.id = forum_posts.user_id')
                    ->orderBy('forum_posts.created_at', 'DESC')
                    ->get()
                    ->getResultArray();
        
        $data = [
            'title' => 'Community Forum',
            'posts' => $posts
        ];
        
        return view('forum/index', $data);
    }
    
    /**
     * View single post with comments
     */
    public function viewPost($id)
    {
        $db = \Config\Database::connect();
        
        $post = $db->table('forum_posts')
                   ->select('forum_posts.*, users.name as author_name, users.role as author_role')
                   ->join('users', 'users.id = forum_posts.user_id')
                   ->where('forum_posts.id', $id)
                   ->get()
                   ->getRowArray();
        
        if (!$post) {
            return redirect()->to('/forum')
                ->with('error', 'Post not found.');
        }
        
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
        $data = [
            'title' => 'Create New Post'
        ];
        
        return view('forum/create', $data);
    }
    
    /**
     * Process create post
     */
    public function createProcess()
    {
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
        
        $data = [
            'user_id' => session()->get('user_id'),
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category') ?? 'general',
            'likes' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($db->table('forum_posts')->insert($data)) {
            return redirect()->to('/forum')
                ->with('success', 'Post created successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create post.');
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
            return redirect()->back()
                ->with('success', 'Comment added!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to add comment.');
        }
    }
    
    /**
     * Like post
     */
    public function likePost($postId)
    {
        $db = \Config\Database::connect();
        
        $db->table('forum_posts')
           ->set('likes', 'likes + 1', false)
           ->where('id', $postId)
           ->update();
        
        return redirect()->back()
            ->with('success', 'Post liked!');
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
