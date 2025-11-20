import { useState } from "react";
import { ArrowLeft, CreditCard, Wallet } from "lucide-react";
import { Page } from "../../App";

interface CheckoutProps {
  navigate: (page: Page) => void;
  cartItems: any[];
  setCartItems: (items: any[]) => void;
}

export default function Checkout({ navigate, cartItems, setCartItems }: CheckoutProps) {
  const [paymentMethod, setPaymentMethod] = useState<"cod" | "gcash">("cod");
  const [formData, setFormData] = useState({
    fullName: "",
    phone: "",
    address: "",
    notes: ""
  });

  const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const deliveryFee = 50;
  const total = subtotal + deliveryFee;

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert("Order placed successfully! The farmer will contact you soon.");
    setCartItems([]);
    navigate("buyer-orders");
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-4xl">
        <button
          onClick={() => navigate("cart")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back to Cart</span>
        </button>

        <h1 className="mb-8">Checkout</h1>

        <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Form */}
          <div className="lg:col-span-2 space-y-6">
            {/* Delivery Information */}
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <h3 className="mb-4">Delivery Information</h3>
              
              <div className="space-y-4">
                <div>
                  <label htmlFor="fullName" className="block mb-2">
                    Full Name <span className="text-error">*</span>
                  </label>
                  <input
                    id="fullName"
                    type="text"
                    value={formData.fullName}
                    onChange={(e) => setFormData({ ...formData, fullName: e.target.value })}
                    placeholder="Enter your full name"
                    className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                    required
                  />
                </div>

                <div>
                  <label htmlFor="phone" className="block mb-2">
                    Contact Number <span className="text-error">*</span>
                  </label>
                  <input
                    id="phone"
                    type="tel"
                    value={formData.phone}
                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                    placeholder="09XX XXX XXXX"
                    className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                    required
                  />
                </div>

                <div>
                  <label htmlFor="address" className="block mb-2">
                    Delivery Address <span className="text-error">*</span>
                  </label>
                  <textarea
                    id="address"
                    value={formData.address}
                    onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                    placeholder="House/Unit #, Street, Barangay, Town"
                    rows={3}
                    className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary resize-none"
                    required
                  />
                </div>

                <div>
                  <label htmlFor="notes" className="block mb-2">
                    Order Notes (Optional)
                  </label>
                  <textarea
                    id="notes"
                    value={formData.notes}
                    onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                    placeholder="Add any special instructions..."
                    rows={2}
                    className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary resize-none"
                  />
                </div>
              </div>
            </div>

            {/* Payment Method */}
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <h3 className="mb-4">Payment Method</h3>
              
              <div className="space-y-3">
                <button
                  type="button"
                  onClick={() => setPaymentMethod("cod")}
                  className={`w-full flex items-center gap-4 p-4 border-2 rounded-lg transition-colors ${
                    paymentMethod === "cod"
                      ? "border-primary bg-primary/5"
                      : "border-neutral-200 hover:border-primary"
                  }`}
                >
                  <div className={`w-6 h-6 rounded-full border-2 flex items-center justify-center ${
                    paymentMethod === "cod" ? "border-primary" : "border-neutral-300"
                  }`}>
                    {paymentMethod === "cod" && <div className="w-3 h-3 rounded-full bg-primary" />}
                  </div>
                  <Wallet className="w-6 h-6 text-neutral-600" />
                  <div className="text-left">
                    <div style={{ fontWeight: "600" }}>Cash on Delivery</div>
                    <div className="text-neutral-600" style={{ fontSize: "0.875rem" }}>
                      Pay when you receive your order
                    </div>
                  </div>
                </button>

                <button
                  type="button"
                  onClick={() => setPaymentMethod("gcash")}
                  className={`w-full flex items-center gap-4 p-4 border-2 rounded-lg transition-colors ${
                    paymentMethod === "gcash"
                      ? "border-primary bg-primary/5"
                      : "border-neutral-200 hover:border-primary"
                  }`}
                >
                  <div className={`w-6 h-6 rounded-full border-2 flex items-center justify-center ${
                    paymentMethod === "gcash" ? "border-primary" : "border-neutral-300"
                  }`}>
                    {paymentMethod === "gcash" && <div className="w-3 h-3 rounded-full bg-primary" />}
                  </div>
                  <CreditCard className="w-6 h-6 text-neutral-600" />
                  <div className="text-left">
                    <div style={{ fontWeight: "600" }}>GCash</div>
                    <div className="text-neutral-600" style={{ fontSize: "0.875rem" }}>
                      Digital payment via GCash
                    </div>
                  </div>
                </button>
              </div>

              {paymentMethod === "gcash" && (
                <div className="mt-4 p-4 bg-info/10 rounded-lg">
                  <p className="text-neutral-700" style={{ fontSize: "0.875rem" }}>
                    📱 You will receive GCash payment instructions after placing your order.
                  </p>
                </div>
              )}
            </div>
          </div>

          {/* Order Summary */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6 sticky top-24">
              <h3 className="mb-4">Order Summary</h3>
              
              <div className="space-y-3 mb-4 pb-4 border-b border-neutral-200">
                {cartItems.map((item, index) => (
                  <div key={index} className="flex justify-between text-neutral-700" style={{ fontSize: "0.875rem" }}>
                    <span>{item.name} x{item.quantity}</span>
                    <span>₱{(item.price * item.quantity).toFixed(2)}</span>
                  </div>
                ))}
              </div>

              <div className="space-y-2 mb-4 pb-4 border-b border-neutral-200">
                <div className="flex justify-between">
                  <span className="text-neutral-600">Subtotal</span>
                  <span style={{ fontWeight: "600" }}>₱{subtotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-neutral-600">Delivery Fee</span>
                  <span style={{ fontWeight: "600" }}>₱{deliveryFee.toFixed(2)}</span>
                </div>
              </div>

              <div className="flex justify-between mb-6">
                <span style={{ fontSize: "1.25rem", fontWeight: "600" }}>Total</span>
                <span className="text-primary" style={{ fontSize: "1.5rem", fontWeight: "700" }}>
                  ₱{total.toFixed(2)}
                </span>
              </div>

              <button
                type="submit"
                className="w-full py-4 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
              >
                Place Order
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}
