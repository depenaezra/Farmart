import { ArrowLeft, Trash2, Plus, Minus, ShoppingBag } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface CartProps {
  navigate: (page: Page) => void;
  cartItems: any[];
  setCartItems: (items: any[]) => void;
}

export default function Cart({ navigate, cartItems, setCartItems }: CartProps) {
  const updateQuantity = (cartId: number, newQuantity: number) => {
    if (newQuantity < 1) return;
    setCartItems(cartItems.map(item =>
      item.cartId === cartId ? { ...item, quantity: newQuantity } : item
    ));
  };

  const removeItem = (cartId: number) => {
    setCartItems(cartItems.filter(item => item.cartId !== cartId));
  };

  const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const deliveryFee = subtotal > 0 ? 50 : 0;
  const total = subtotal + deliveryFee;

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-4xl">
        <button
          onClick={() => navigate("marketplace")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Continue Shopping</span>
        </button>

        <h1 className="mb-8">Shopping Cart</h1>

        {cartItems.length === 0 ? (
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-12 text-center">
            <ShoppingBag className="w-16 h-16 text-neutral-300 mx-auto mb-4" />
            <h3 className="mb-2">Your cart is empty</h3>
            <p className="text-neutral-600 mb-6">Add some fresh produce from our farmers!</p>
            <button
              onClick={() => navigate("marketplace")}
              className="px-8 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
            >
              Browse Marketplace
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Cart Items */}
            <div className="lg:col-span-2 space-y-4">
              {cartItems.map((item) => (
                <div key={item.cartId} className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-4">
                  <div className="flex gap-4">
                    <ImageWithFallback
                      src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=150&h=150&fit=crop&q=80`}
                      alt={item.name}
                      className="w-24 h-24 object-cover rounded-lg flex-shrink-0"
                    />
                    
                    <div className="flex-1">
                      <div className="flex items-start justify-between mb-2">
                        <div>
                          <h3 className="mb-1">{item.name}</h3>
                          <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>
                            👨‍🌾 {item.farmer}
                          </p>
                        </div>
                        <button
                          onClick={() => removeItem(item.cartId)}
                          className="text-error hover:bg-red-50 p-2 rounded-lg transition-colors"
                        >
                          <Trash2 className="w-5 h-5" />
                        </button>
                      </div>

                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                          <button
                            onClick={() => updateQuantity(item.cartId, item.quantity - 1)}
                            className="w-10 h-10 bg-neutral-200 hover:bg-neutral-300 rounded-lg transition-colors"
                          >
                            <Minus className="w-4 h-4 mx-auto" />
                          </button>
                          <span style={{ fontSize: "1.125rem", fontWeight: "600" }}>
                            {item.quantity}
                          </span>
                          <button
                            onClick={() => updateQuantity(item.cartId, item.quantity + 1)}
                            className="w-10 h-10 bg-neutral-200 hover:bg-neutral-300 rounded-lg transition-colors"
                          >
                            <Plus className="w-4 h-4 mx-auto" />
                          </button>
                        </div>

                        <div className="text-right">
                          <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>
                            ₱{item.price} per {item.unit}
                          </p>
                          <p className="text-primary" style={{ fontSize: "1.25rem", fontWeight: "600" }}>
                            ₱{(item.price * item.quantity).toFixed(2)}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6 sticky top-24">
                <h3 className="mb-6">Order Summary</h3>
                
                <div className="space-y-3 mb-6 pb-6 border-b border-neutral-200">
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
                  onClick={() => navigate("checkout")}
                  className="w-full py-4 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors mb-3"
                >
                  Proceed to Checkout
                </button>

                <button
                  onClick={() => navigate("marketplace")}
                  className="w-full py-3 border-2 border-neutral-300 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  Continue Shopping
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
