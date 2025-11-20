import { Megaphone, Calendar, Tag } from "lucide-react";
import { Page, UserRole } from "../../App";

interface AnnouncementsProps {
  navigate: (page: Page) => void;
  userRole: UserRole;
}

const announcements = [
  {
    id: "1",
    title: "New Government Training Program: Organic Farming",
    category: "Training",
    date: "Nov 18, 2024",
    author: "DA-Batangas",
    content: "The Department of Agriculture is offering a free 3-day training on organic farming methods. Registration is now open for all farmers in Nasugbu. Topics include composting, natural pest control, and organic certification. Limited slots available!",
    priority: "high"
  },
  {
    id: "2",
    title: "Subsidy Program for Farm Equipment",
    category: "Subsidies",
    date: "Nov 15, 2024",
    author: "LGU Nasugbu",
    content: "The Local Government Unit is providing subsidies for farmers purchasing farming equipment. Up to 50% discount available for tillers, sprayers, and irrigation systems. Apply at the Municipal Agriculture Office.",
    priority: "high"
  },
  {
    id: "3",
    title: "Weather Advisory: Heavy Rainfall This Weekend",
    category: "Weather",
    date: "Nov 14, 2024",
    author: "PAGASA",
    content: "PAGASA advises farmers to prepare for heavy rainfall this weekend. Secure your crops and check drainage systems. Planting activities should be postponed until weather conditions improve.",
    priority: "urgent"
  },
  {
    id: "4",
    title: "Monthly Farmers' Meeting - December 2024",
    category: "Events",
    date: "Nov 10, 2024",
    author: "Nasugbu Farmers Coop",
    content: "Join us for our monthly farmers' meeting on December 1, 2024, at the Barangay Hall. Topics: Market trends, pest management, and cooperative updates. Snacks will be provided.",
    priority: "normal"
  },
  {
    id: "5",
    title: "Free Seeds Distribution Program",
    category: "Subsidies",
    date: "Nov 5, 2024",
    author: "DA-Batangas",
    content: "The Department of Agriculture will distribute free vegetable seeds to registered farmers. Available varieties: tomatoes, eggplant, lettuce, and peppers. Claim your seeds at the Municipal Agriculture Office.",
    priority: "normal"
  }
];

export default function Announcements({ navigate, userRole }: AnnouncementsProps) {
  const getPriorityColor = (priority: string) => {
    if (priority === "urgent") return "border-error bg-error/10";
    if (priority === "high") return "border-warning bg-warning/10";
    return "border-info bg-info/10";
  };

  const getPriorityBadge = (priority: string) => {
    if (priority === "urgent") return "bg-error";
    if (priority === "high") return "bg-warning";
    return "bg-info";
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-5xl">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div>
            <h1 className="mb-2">Announcements</h1>
            <p className="text-neutral-600">Important updates for the farming community</p>
          </div>
          {userRole === "admin" && (
            <button
              onClick={() => navigate("post-announcement")}
              className="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
            >
              <Megaphone className="w-5 h-5" />
              <span>Post Announcement</span>
            </button>
          )}
        </div>

        {/* Filter Badges */}
        <div className="flex gap-2 mb-8 overflow-x-auto pb-2">
          <button className="px-4 py-2 bg-primary text-white rounded-full whitespace-nowrap">
            All
          </button>
          <button className="px-4 py-2 bg-white border-2 border-neutral-200 hover:border-primary rounded-full whitespace-nowrap">
            Training
          </button>
          <button className="px-4 py-2 bg-white border-2 border-neutral-200 hover:border-primary rounded-full whitespace-nowrap">
            Subsidies
          </button>
          <button className="px-4 py-2 bg-white border-2 border-neutral-200 hover:border-primary rounded-full whitespace-nowrap">
            Weather
          </button>
          <button className="px-4 py-2 bg-white border-2 border-neutral-200 hover:border-primary rounded-full whitespace-nowrap">
            Events
          </button>
        </div>

        {/* Announcements List */}
        <div className="space-y-6">
          {announcements.map((announcement) => (
            <div
              key={announcement.id}
              className={`bg-white rounded-xl shadow-md border-2 ${getPriorityColor(announcement.priority)} p-6 hover:shadow-lg transition-shadow`}
            >
              <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                <div className="flex-1">
                  <div className="flex items-start gap-3 mb-2">
                    <div className="bg-primary rounded-full p-2 flex-shrink-0">
                      <Megaphone className="w-5 h-5 text-white" />
                    </div>
                    <div className="flex-1">
                      <h3 className="mb-2">{announcement.title}</h3>
                      <div className="flex flex-wrap items-center gap-3 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                        <span className="flex items-center gap-1">
                          <Calendar className="w-4 h-4" />
                          {announcement.date}
                        </span>
                        <span className="flex items-center gap-1">
                          <Tag className="w-4 h-4" />
                          {announcement.category}
                        </span>
                        <span>By {announcement.author}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <span
                  className={`${getPriorityBadge(announcement.priority)} text-white px-3 py-1 rounded-full whitespace-nowrap`}
                  style={{ fontSize: "0.875rem" }}
                >
                  {announcement.priority === "urgent" ? "🚨 Urgent" :
                   announcement.priority === "high" ? "⚠️ Important" : "ℹ️ Info"}
                </span>
              </div>

              <p className="text-neutral-700 leading-relaxed">{announcement.content}</p>

              {announcement.priority === "urgent" && (
                <div className="mt-4 pt-4 border-t border-neutral-200">
                  <button className="text-primary hover:underline">
                    Read More →
                  </button>
                </div>
              )}
            </div>
          ))}
        </div>

        {/* Government Programs Section */}
        <div className="mt-12">
          <h2 className="mb-6">Government Programs & Support</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center mb-4">
                <span style={{ fontSize: "1.5rem" }}>🎓</span>
              </div>
              <h4 className="mb-2">Training Programs</h4>
              <p className="text-neutral-600 mb-4" style={{ fontSize: "0.875rem" }}>
                Free agricultural training and workshops for farmers
              </p>
              <button className="text-primary hover:underline">Learn More →</button>
            </div>

            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center mb-4">
                <span style={{ fontSize: "1.5rem" }}>💰</span>
              </div>
              <h4 className="mb-2">Financial Assistance</h4>
              <p className="text-neutral-600 mb-4" style={{ fontSize: "0.875rem" }}>
                Subsidies and loans for agricultural development
              </p>
              <button className="text-primary hover:underline">Learn More →</button>
            </div>

            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                <span style={{ fontSize: "1.5rem" }}>🌱</span>
              </div>
              <h4 className="mb-2">Technical Support</h4>
              <p className="text-neutral-600 mb-4" style={{ fontSize: "0.875rem" }}>
                Agricultural advisories and expert consultation
              </p>
              <button className="text-primary hover:underline">Learn More →</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
