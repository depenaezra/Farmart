import { useState } from "react";
import { MessageSquare, ThumbsUp, Plus } from "lucide-react";
import { Page, UserRole } from "../../App";

interface ForumProps {
  navigate: (page: Page) => void;
  userRole: UserRole;
}

const categories = ["All", "Farming Tips", "Pests & Diseases", "Market Prices", "Government Programs", "Equipment"];

const posts = [
  {
    id: "1",
    category: "Farming Tips",
    title: "Best time to plant tomatoes in Nasugbu?",
    author: "Juan Santos",
    date: "2 hours ago",
    content: "Hello fellow farmers! I'm planning to plant tomatoes next month. When is the best time considering our weather in Nasugbu?",
    replies: 5,
    likes: 12
  },
  {
    id: "2",
    category: "Pests & Diseases",
    title: "Natural remedies for aphids on lettuce",
    author: "Maria Cruz",
    date: "5 hours ago",
    content: "My lettuce crops are being attacked by aphids. Does anyone know effective natural remedies? I prefer organic solutions.",
    replies: 8,
    likes: 15
  },
  {
    id: "3",
    category: "Market Prices",
    title: "Corn prices this week?",
    author: "Pedro Reyes",
    date: "1 day ago",
    content: "What are the current market prices for corn in nearby towns? Planning to harvest next week.",
    replies: 3,
    likes: 7
  },
  {
    id: "4",
    category: "Equipment",
    title: "Recommendations for affordable irrigation system",
    author: "Rosa Garcia",
    date: "2 days ago",
    content: "Looking for recommendations on affordable irrigation systems for a 2-hectare farm. What are you using?",
    replies: 12,
    likes: 20
  }
];

export default function Forum({ navigate, userRole }: ForumProps) {
  const [selectedCategory, setSelectedCategory] = useState("All");

  const filteredPosts = selectedCategory === "All" 
    ? posts 
    : posts.filter(post => post.category === selectedCategory);

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-5xl">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div>
            <h1 className="mb-2">Community Forum</h1>
            <p className="text-neutral-600">Connect and learn from fellow farmers</p>
          </div>
          {userRole !== "guest" && (
            <button className="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors">
              <Plus className="w-5 h-5" />
              <span>New Post</span>
            </button>
          )}
        </div>

        {/* Categories */}
        <div className="flex gap-2 mb-8 overflow-x-auto pb-2">
          {categories.map((cat) => (
            <button
              key={cat}
              onClick={() => setSelectedCategory(cat)}
              className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
                selectedCategory === cat
                  ? "bg-primary text-white"
                  : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Posts */}
        <div className="space-y-4">
          {filteredPosts.map((post) => (
            <div
              key={post.id}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6 hover:border-primary hover:shadow-lg transition-all cursor-pointer"
            >
              <div className="flex items-start gap-4 mb-4">
                <div className="w-12 h-12 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                  <span className="text-white" style={{ fontSize: "1.25rem" }}>
                    {post.author.charAt(0)}
                  </span>
                </div>
                <div className="flex-1">
                  <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                    <div>
                      <h3 className="mb-1">{post.title}</h3>
                      <div className="flex items-center gap-3 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                        <span>👨‍🌾 {post.author}</span>
                        <span>•</span>
                        <span>{post.date}</span>
                      </div>
                    </div>
                    <span className="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full w-fit" style={{ fontSize: "0.875rem" }}>
                      {post.category}
                    </span>
                  </div>
                  <p className="text-neutral-700 mb-4">{post.content}</p>
                  
                  <div className="flex items-center gap-6">
                    <button className="flex items-center gap-2 text-neutral-600 hover:text-primary transition-colors">
                      <MessageSquare className="w-5 h-5" />
                      <span>{post.replies} Replies</span>
                    </button>
                    <button className="flex items-center gap-2 text-neutral-600 hover:text-primary transition-colors">
                      <ThumbsUp className="w-5 h-5" />
                      <span>{post.likes} Likes</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Popular Topics */}
        <div className="mt-12 bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
          <h3 className="mb-6">📌 Popular Topics This Week</h3>
          <div className="space-y-3">
            {["Organic fertilizer making", "Pest control for tomatoes", "Government subsidy application", "Water-saving irrigation"].map((topic, index) => (
              <button
                key={index}
                className="w-full text-left p-3 hover:bg-neutral-50 rounded-lg transition-colors"
              >
                <div className="flex items-center gap-3">
                  <span className="text-primary" style={{ fontSize: "1.25rem", fontWeight: "700" }}>
                    {index + 1}
                  </span>
                  <span>{topic}</span>
                </div>
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
