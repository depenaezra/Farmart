import { useState } from "react";
import { ArrowLeft, Megaphone } from "lucide-react";
import { Page } from "../../App";

interface PostAnnouncementProps {
  navigate: (page: Page) => void;
}

export default function PostAnnouncement({ navigate }: PostAnnouncementProps) {
  const [formData, setFormData] = useState({
    title: "",
    category: "",
    priority: "normal",
    content: ""
  });

  const handleChange = (field: string, value: string) => {
    setFormData({ ...formData, [field]: value });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert("Announcement posted successfully!");
    navigate("announcements");
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-3xl">
        <button
          onClick={() => navigate("admin-dashboard")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back to Dashboard</span>
        </button>

        <div className="mb-8">
          <h1>Post Announcement</h1>
          <p className="text-neutral-600">Create a new announcement for the community</p>
        </div>

        <form onSubmit={handleSubmit} className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-8">
          <div className="space-y-6">
            {/* Title */}
            <div>
              <label htmlFor="title" className="block mb-2">
                Announcement Title <span className="text-error">*</span>
              </label>
              <input
                id="title"
                type="text"
                value={formData.title}
                onChange={(e) => handleChange("title", e.target.value)}
                placeholder="Enter announcement title"
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                required
              />
            </div>

            {/* Category */}
            <div>
              <label htmlFor="category" className="block mb-2">
                Category <span className="text-error">*</span>
              </label>
              <select
                id="category"
                value={formData.category}
                onChange={(e) => handleChange("category", e.target.value)}
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                required
              >
                <option value="">Select category</option>
                <option value="Training">Training</option>
                <option value="Subsidies">Subsidies</option>
                <option value="Weather">Weather</option>
                <option value="Events">Events</option>
                <option value="General">General</option>
              </select>
            </div>

            {/* Priority */}
            <div>
              <label className="block mb-2">
                Priority Level <span className="text-error">*</span>
              </label>
              <div className="grid grid-cols-3 gap-3">
                <button
                  type="button"
                  onClick={() => handleChange("priority", "normal")}
                  className={`py-3 px-4 border-2 rounded-lg transition-all ${
                    formData.priority === "normal"
                      ? "border-info bg-info/10 text-info"
                      : "border-neutral-300 hover:border-info"
                  }`}
                >
                  ℹ️ Normal
                </button>
                <button
                  type="button"
                  onClick={() => handleChange("priority", "high")}
                  className={`py-3 px-4 border-2 rounded-lg transition-all ${
                    formData.priority === "high"
                      ? "border-warning bg-warning/10 text-warning"
                      : "border-neutral-300 hover:border-warning"
                  }`}
                >
                  ⚠️ Important
                </button>
                <button
                  type="button"
                  onClick={() => handleChange("priority", "urgent")}
                  className={`py-3 px-4 border-2 rounded-lg transition-all ${
                    formData.priority === "urgent"
                      ? "border-error bg-error/10 text-error"
                      : "border-neutral-300 hover:border-error"
                  }`}
                >
                  🚨 Urgent
                </button>
              </div>
            </div>

            {/* Content */}
            <div>
              <label htmlFor="content" className="block mb-2">
                Announcement Content <span className="text-error">*</span>
              </label>
              <textarea
                id="content"
                value={formData.content}
                onChange={(e) => handleChange("content", e.target.value)}
                placeholder="Enter the full announcement message..."
                rows={8}
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary resize-none"
                required
              />
              <p className="text-neutral-500 mt-2" style={{ fontSize: "0.875rem" }}>
                {formData.content.length} characters
              </p>
            </div>

            {/* Preview */}
            {(formData.title || formData.content) && (
              <div className="bg-neutral-50 p-6 rounded-lg border-2 border-neutral-200">
                <h4 className="mb-4">Preview</h4>
                <div className={`p-4 rounded-lg border-l-4 ${
                  formData.priority === "urgent" ? "bg-error/10 border-error" :
                  formData.priority === "high" ? "bg-warning/10 border-warning" :
                  "bg-info/10 border-info"
                }`}>
                  <div className="flex items-start gap-3 mb-3">
                    <Megaphone className="w-5 h-5 text-primary" />
                    <h3>{formData.title || "Announcement Title"}</h3>
                  </div>
                  <p className="text-neutral-700">{formData.content || "Announcement content will appear here..."}</p>
                  {formData.category && (
                    <span className="inline-block mt-3 px-3 py-1 bg-primary/10 text-primary rounded-full" style={{ fontSize: "0.875rem" }}>
                      {formData.category}
                    </span>
                  )}
                </div>
              </div>
            )}

            {/* Action Buttons */}
            <div className="flex gap-4 pt-4">
              <button
                type="button"
                onClick={() => navigate("admin-dashboard")}
                className="flex-1 py-3 border-2 border-neutral-300 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                className="flex-1 flex items-center justify-center gap-2 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
              >
                <Megaphone className="w-5 h-5" />
                <span>Post Announcement</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}
