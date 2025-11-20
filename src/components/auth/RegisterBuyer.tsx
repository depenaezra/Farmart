import { useState } from "react";
import { User, Phone, Mail, Lock, ShoppingBag } from "lucide-react";
import { Page } from "../../App";

interface RegisterBuyerProps {
  navigate: (page: Page) => void;
}

export default function RegisterBuyer({ navigate }: RegisterBuyerProps) {
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    phone: "",
    address: "",
    password: "",
    confirmPassword: ""
  });

  const handleChange = (field: string, value: string) => {
    setFormData({ ...formData, [field]: value });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Mock registration
    alert("Registration successful! Please check your email to verify your account.");
    navigate("login");
  };

  return (
    <div className="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 bg-neutral-50">
      <div className="w-full max-w-md">
        <div className="bg-white rounded-2xl shadow-lg border-2 border-neutral-200 p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
              <ShoppingBag className="w-10 h-10 text-white" />
            </div>
            <h2 className="mb-2">Buyer Registration</h2>
            <p className="text-neutral-600">Start buying fresh produce from local farmers</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="fullName" className="block mb-2">
                Full Name <span className="text-error">*</span>
              </label>
              <div className="relative">
                <User className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="fullName"
                  type="text"
                  value={formData.fullName}
                  onChange={(e) => handleChange("fullName", e.target.value)}
                  placeholder="Enter your full name"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div>
              <label htmlFor="email" className="block mb-2">
                Email Address <span className="text-error">*</span>
              </label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="email"
                  type="email"
                  value={formData.email}
                  onChange={(e) => handleChange("email", e.target.value)}
                  placeholder="your.email@example.com"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div>
              <label htmlFor="phone" className="block mb-2">
                Contact Number <span className="text-error">*</span>
              </label>
              <div className="relative">
                <Phone className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="phone"
                  type="tel"
                  value={formData.phone}
                  onChange={(e) => handleChange("phone", e.target.value)}
                  placeholder="09XX XXX XXXX"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div>
              <label htmlFor="address" className="block mb-2">
                Delivery Address
              </label>
              <textarea
                id="address"
                value={formData.address}
                onChange={(e) => handleChange("address", e.target.value)}
                placeholder="Street, Barangay, Town"
                rows={3}
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary resize-none"
              />
            </div>

            <div>
              <label htmlFor="password" className="block mb-2">
                Password <span className="text-error">*</span>
              </label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="password"
                  type="password"
                  value={formData.password}
                  onChange={(e) => handleChange("password", e.target.value)}
                  placeholder="Create a strong password"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div>
              <label htmlFor="confirmPassword" className="block mb-2">
                Confirm Password <span className="text-error">*</span>
              </label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input
                  id="confirmPassword"
                  type="password"
                  value={formData.confirmPassword}
                  onChange={(e) => handleChange("confirmPassword", e.target.value)}
                  placeholder="Re-enter your password"
                  className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                />
              </div>
            </div>

            <div className="bg-accent/10 p-4 rounded-lg">
              <p className="text-neutral-700" style={{ fontSize: "0.875rem" }}>
                ✅ By registering, you agree to AgriConnect's Terms of Service and Privacy Policy
              </p>
            </div>

            <button
              type="submit"
              className="w-full py-4 bg-accent text-white hover:bg-accent-hover rounded-lg transition-colors"
            >
              Create Account
            </button>
          </form>

          <div className="mt-6 text-center">
            <p className="text-neutral-600">
              Already have an account?{" "}
              <button
                onClick={() => navigate("login")}
                className="text-primary hover:underline"
              >
                Sign In
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
