import { useState } from "react";
import { ArrowLeft, Send, Search } from "lucide-react";
import { Page, UserRole } from "../../App";

interface InboxProps {
  navigate: (page: Page) => void;
  userRole: UserRole;
}

const mockConversations = [
  { id: "1", name: "Maria Santos", lastMessage: "Is the delivery still scheduled for today?", time: "10:30 AM", unread: true },
  { id: "2", name: "Juan Cruz", lastMessage: "Thank you for the fresh produce!", time: "Yesterday", unread: false },
  { id: "3", name: "Rosa Garcia", lastMessage: "Can I order 20kg more?", time: "Nov 18", unread: false }
];

const mockMessages = [
  { id: "1", sender: "Maria Santos", message: "Hi! I'm interested in your tomatoes", time: "10:15 AM", isOwn: false },
  { id: "2", sender: "You", message: "Hello! Yes, we have fresh tomatoes available", time: "10:20 AM", isOwn: true },
  { id: "3", sender: "Maria Santos", message: "Is the delivery still scheduled for today?", time: "10:30 AM", isOwn: false }
];

export default function Inbox({ navigate, userRole }: InboxProps) {
  const [selectedConversation, setSelectedConversation] = useState("1");
  const [newMessage, setNewMessage] = useState("");
  const [searchTerm, setSearchTerm] = useState("");

  const handleSendMessage = (e: React.FormEvent) => {
    e.preventDefault();
    if (newMessage.trim()) {
      alert("Message sent!");
      setNewMessage("");
    }
  };

  const filteredConversations = mockConversations.filter(conv =>
    conv.name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-6xl">
        <button
          onClick={() => navigate(userRole === "farmer" ? "farmer-dashboard" : "marketplace")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back</span>
        </button>

        <h1 className="mb-8">Messages</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Conversations List */}
          <div className="lg:col-span-1 bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden">
            <div className="p-4 border-b border-neutral-200">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Search conversations..."
                  className="w-full pl-10 pr-4 py-2 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                />
              </div>
            </div>

            <div className="divide-y divide-neutral-200 max-h-[600px] overflow-y-auto">
              {filteredConversations.map((conv) => (
                <button
                  key={conv.id}
                  onClick={() => setSelectedConversation(conv.id)}
                  className={`w-full p-4 text-left hover:bg-neutral-50 transition-colors ${
                    selectedConversation === conv.id ? "bg-primary/5 border-l-4 border-primary" : ""
                  }`}
                >
                  <div className="flex items-start justify-between mb-1">
                    <h4 className={conv.unread ? "text-primary" : ""}>{conv.name}</h4>
                    {conv.unread && (
                      <span className="w-3 h-3 bg-primary rounded-full"></span>
                    )}
                  </div>
                  <p className="text-neutral-600 truncate" style={{ fontSize: "0.875rem" }}>
                    {conv.lastMessage}
                  </p>
                  <p className="text-neutral-500 mt-1" style={{ fontSize: "0.75rem" }}>
                    {conv.time}
                  </p>
                </button>
              ))}
            </div>
          </div>

          {/* Chat Area */}
          <div className="lg:col-span-2 bg-white rounded-xl shadow-md border-2 border-neutral-200 flex flex-col" style={{ height: "600px" }}>
            {/* Chat Header */}
            <div className="p-4 border-b border-neutral-200">
              <h3>Maria Santos</h3>
              <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>Buyer from Nasugbu</p>
            </div>

            {/* Messages */}
            <div className="flex-1 overflow-y-auto p-4 space-y-4">
              {mockMessages.map((msg) => (
                <div
                  key={msg.id}
                  className={`flex ${msg.isOwn ? "justify-end" : "justify-start"}`}
                >
                  <div className={`max-w-[70%] ${msg.isOwn ? "bg-primary text-white" : "bg-neutral-100 text-neutral-900"} rounded-lg p-3`}>
                    {!msg.isOwn && (
                      <p style={{ fontSize: "0.75rem", fontWeight: "600" }} className="mb-1">
                        {msg.sender}
                      </p>
                    )}
                    <p>{msg.message}</p>
                    <p className={`${msg.isOwn ? "text-white/80" : "text-neutral-500"} mt-1`} style={{ fontSize: "0.75rem" }}>
                      {msg.time}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Message Input */}
            <form onSubmit={handleSendMessage} className="p-4 border-t border-neutral-200">
              <div className="flex gap-2">
                <input
                  type="text"
                  value={newMessage}
                  onChange={(e) => setNewMessage(e.target.value)}
                  placeholder="Type your message..."
                  className="flex-1 px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                />
                <button
                  type="submit"
                  className="flex items-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                >
                  <Send className="w-5 h-5" />
                  <span className="hidden sm:inline">Send</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}
