import { useState } from "react";
import { User, Phone, MapPin, Building2, Mail, Lock, ArrowRight, ArrowLeft } from "lucide-react";
import { Page } from "../../App";

interface RegisterFarmerProps {
  navigate: (page: Page) => void;
}

export default function RegisterFarmer({ navigate }: RegisterFarmerProps) {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    phone: "",
    cooperative: "",
    farmLocation: "",
    password: "",
    confirmPassword: ""
  });

  const handleChange = (field: string, value: string) => {
    setFormData({ ...formData, [field]: value });
  };

  const handleNext = () => {
    if (step < 3) setStep(step + 1);
  };

  const handleBack = () => {
    if (step > 1) setStep(step - 1);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Mock registration - in real app, send to backend
    alert("Registration successful! Please check your email to verify your account.");
    navigate("login");
  };

  return (
    <div className="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 bg-neutral-50">
      <div className="w-full max-w-2xl">
        <div className="bg-white rounded-2xl shadow-lg border-2 border-neutral-200 p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
              <span className="text-white" style={{ fontSize: "2rem" }}>🌾</span>
            </div>
            <h2 className="mb-2">Farmer Registration</h2>
            <p className="text-neutral-600">Join AgriConnect and start selling your produce</p>
          </div>

          {/* Progress Steps */}
          <div className="flex items-center justify-between mb-8">
            {[1, 2, 3].map((num) => (
              <div key={num} className="flex items-center flex-1">
                <div
                  className={`w-10 h-10 rounded-full flex items-center justify-center transition-colors ${
                    step >= num
                      ? "bg-primary text-white"
                      : "bg-neutral-200 text-neutral-500"
                  }`}
                >
                  {num}
                </div>
                {num < 3 && (
                  <div
                    className={`flex-1 h-1 mx-2 transition-colors ${
                      step > num ? "bg-primary" : "bg-neutral-200"
                    }`}
                  />
                )}
              </div>
            ))}
          </div>

          <form onSubmit={handleSubmit}>
            {/* Step 1: Personal Information */}
            {step === 1 && (
              <div className="space-y-6">
                <h3 className="mb-4">Personal Information</h3>
                
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
              </div>
            )}

            {/* Step 2: Farm Information */}
            {step === 2 && (
              <div className="space-y-6">
                <h3 className="mb-4">Farm Information</h3>
                
                <div>
                  <label htmlFor="cooperative" className="block mb-2">
                    Cooperative / Association
                  </label>
                  <div className="relative">
                    <Building2 className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                    <select
                      id="cooperative"
                      value={formData.cooperative}
                      onChange={(e) => handleChange("cooperative", e.target.value)}
                      className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                    >
                      <option value="">Select your cooperative</option>
                      <option value="nasugbu-farmers">Nasugbu Farmers Cooperative</option>
                      <option value="green-valley">Green Valley Cooperative</option>
                      <option value="batangas-corn">Batangas Corn Growers</option>
                      <option value="independent">Independent Farmer</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label htmlFor="farmLocation" className="block mb-2">
                    Farm Location <span className="text-error">*</span>
                  </label>
                  <div className="relative">
                    <MapPin className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
                    <input
                      id="farmLocation"
                      type="text"
                      value={formData.farmLocation}
                      onChange={(e) => handleChange("farmLocation", e.target.value)}
                      placeholder="Barangay, Nasugbu, Batangas"
                      className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                      required
                    />
                  </div>
                  <p className="text-neutral-500 mt-2" style={{ fontSize: "0.875rem" }}>
                    Enter your farm's barangay or street address
                  </p>
                </div>

                <div className="bg-neutral-100 p-4 rounded-lg">
                  <p className="text-neutral-700" style={{ fontSize: "0.875rem" }}>
                    💡 <strong>Note:</strong> Your farm location helps buyers find local produce and supports the community marketplace.
                  </p>
                </div>
              </div>
            )}

            {/* Step 3: Account Security */}
            {step === 3 && (
              <div className="space-y-6">
                <h3 className="mb-4">Account Security</h3>
                
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

                <div className="bg-primary/10 p-4 rounded-lg">
                  <p className="text-neutral-700" style={{ fontSize: "0.875rem" }}>
                    ✅ By registering, you agree to AgriConnect's Terms of Service and Privacy Policy
                  </p>
                </div>
              </div>
            )}

            {/* Navigation Buttons */}
            <div className="flex gap-4 mt-8">
              {step > 1 && (
                <button
                  type="button"
                  onClick={handleBack}
                  className="flex items-center justify-center gap-2 px-6 py-3 border-2 border-neutral-300 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  <ArrowLeft className="w-5 h-5" />
                  <span>Back</span>
                </button>
              )}
              
              {step < 3 ? (
                <button
                  type="button"
                  onClick={handleNext}
                  className="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                >
                  <span>Next Step</span>
                  <ArrowRight className="w-5 h-5" />
                </button>
              ) : (
                <button
                  type="submit"
                  className="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                >
                  <span>Complete Registration</span>
                  <ArrowRight className="w-5 h-5" />
                </button>
              )}
            </div>
          </form>

          {/* Login Link */}
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
