import { useState } from "react";
import { User, Lock, LogIn } from "lucide-react";
import { Page, UserRole } from "../../App";

interface LoginProps {
  navigate: (page: Page) => void;
  onLogin: (role: UserRole, name: string) => void;
}

export default function Login({ navigate, onLogin }: LoginProps) {
  const [selectedRole, setSelectedRole] = useState<"farmer" | "buyer" | "admin">("buyer");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Mock login - in real app, validate credentials
    const mockName = selectedRole === "farmer" ? "Juan Santos" : selectedRole === "buyer" ? "Maria Cruz" : "Admin User";
    onLogin(selectedRole, mockName);
  };

  return (
    <div className="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 bg-neutral-50">
      <div className="w-full max-w-md">
        <div className="bg-white rounded-2xl shadow-lg border-2 border-neutral-200 p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
              <LogIn className="w-10 h-10 text-white" />
            </div>
            <h2 className="mb-2">Welcome Back</h2>
            <p className="text-neutral-600">Sign in to your AgriConnect account</p>
          </div>

          {/* Role Selection */}
          <div className="mb-6">
            <label className="block mb-3">Login as:</label>
            <div className="grid grid-cols-3 gap-2">
              <button
                type="button"
                onClick={() => setSelectedRole("farmer")}
                className={`py-3 px-4 rounded-lg border-2 transition-all ${
                  selectedRole === "farmer"
                    ? "bg-primary text-white border-primary"
                    : "bg-white text-neutral-700 border-neutral-300 hover:border-primary"
                }`}
              >
                Farmer
              </button>
              <button
                type="button"
                onClick={() => setSelectedRole("buyer")}
                className={`py-3 px-4 rounded-lg border-2 transition-all ${
                  selectedRole === "buyer"
                    ? "bg-primary text-white border-primary"
                    : "bg-white text-neutral-700 border-neutral-300 hover:border-primary"
                }`}
              >
                Buyer
              </button>
              <button
                type="button"
                onClick={() => setSelectedRole("admin")}
                className={`py-3 px-4 rounded-lg border-2 transition-all ${
                  selectedRole === "admin"
                    ? "bg-primary text-white border-primary"
                    : "bg-white text-neutral-700 border-neutral-300 hover:border-primary"
                }`}
              >
                Admin
              </button>
            </div>
          </div>

          {/* Login Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="email" className="block mb-2">
                Email / Username
              </label>
              <div className="relative">
                <User className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="email"
                  type="text"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email or username"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div>
              <label htmlFor="password" className="block mb-2">
                Password
              </label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="password"
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Enter your password"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <button
              type="submit"
              className="w-full py-4 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <LogIn className="w-5 h-5" />
              <span>Sign In</span>
            </button>
          </form>

          {/* Additional Options */}
          <div className="mt-6 text-center space-y-3">
            <button className="text-primary hover:underline">
              Forgot Password?
            </button>
            
            <div className="border-t border-neutral-200 pt-4">
              <p className="text-neutral-600 mb-3">Don't have an account?</p>
              <div className="flex gap-2">
                <button
                  onClick={() => navigate("register-farmer")}
                  className="flex-1 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg transition-colors"
                >
                  Register as Farmer
                </button>
                <button
                  onClick={() => navigate("register-buyer")}
                  className="flex-1 py-3 border-2 border-accent text-accent hover:bg-accent hover:text-white rounded-lg transition-colors"
                >
                  Register as Buyer
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
