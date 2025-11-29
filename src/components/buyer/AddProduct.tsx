import { useState } from "react";
import { ArrowLeft, Upload, Plus, X } from "lucide-react";
import { Page } from "../../App";

interface BuyerAddProductProps {
  navigate: (page: Page) => void;
}

export default function BuyerAddProduct({ navigate }: BuyerAddProductProps) {
  const [formData, setFormData] = useState({
    cropName: "",
    category: "",
    price: "",
    unit: "kilo",
    stock: "",
    description: ""
  });

  const [images, setImages] = useState<string[]>([]);

  const handleChange = (field: string, value: string) => {
    setFormData({ ...formData, [field]: value });
  };

  const handleImageAdd = () => {
    setImages([
      ...images,
      "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=300&h=300&fit=crop&q=80"
    ]);
  };

  const handleImageRemove = (index: number) => {
    setImages(images.filter((_, i) => i !== index));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert("Product added successfully!");
    navigate("buyer-inventory");
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-3xl">
        <div className="mb-8">
          <button
            onClick={() => navigate("buyer-dashboard")}
            className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-4"
          >
            <ArrowLeft className="w-5 h-5" />
            <span>Back to Dashboard</span>
          </button>
          <h1>Add New Product</h1>
          <p className="text-neutral-600">Fill in the details to post your product</p>
        </div>

        <form
          onSubmit={handleSubmit}
          className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-8"
        >
          <div className="space-y-6">
            <div>
              <label htmlFor="cropName" className="block mb-2">
                Product Name <span className="text-error">*</span>
              </label>
              <input
                id="cropName"
                type="text"
                value={formData.cropName}
                onChange={(e) => handleChange("cropName", e.target.value)}
                placeholder="e.g., Tomatoes, Lettuce, Corn"
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                required
              />
            </div>

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
                <option value="vegetables">Vegetables</option>
                <option value="fruits">Fruits</option>
                <option value="grains">Grains & Cereals</option>
                <option value="herbs">Herbs & Spices</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label htmlFor="price" className="block mb-2">
                  Price <span className="text-error">*</span>
                </label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                  <input
                    id="price"
                    type="number"
                    value={formData.price}
                    onChange={(e) => handleChange("price", e.target.value)}
                    placeholder="0.00"
                    className="w-full pl-8 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                    required
                  />
                </div>
              </div>

              <div>
                <label htmlFor="unit" className="block mb-2">
                  Per Unit <span className="text-error">*</span>
                </label>
                <select
                  id="unit"
                  value={formData.unit}
                  onChange={(e) => handleChange("unit", e.target.value)}
                  className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                  required
                >
                  <option value="kilo">Per Kilo</option>
                  <option value="piece">Per Piece</option>
                  <option value="bundle">Per Bundle</option>
                  <option value="sack">Per Sack</option>
                </select>
              </div>
            </div>

            <div>
              <label htmlFor="stock" className="block mb-2">
                Stock Quantity <span className="text-error">*</span>
              </label>
              <input
                id="stock"
                type="number"
                value={formData.stock}
                onChange={(e) => handleChange("stock", e.target.value)}
                placeholder="Enter available quantity"
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                required
              />
              <p className="text-neutral-500 mt-2" style={{ fontSize: "0.875rem" }}>
                How many units do you have available?
              </p>
            </div>

            <div>
              <label className="block mb-2">Product Photos</label>

              <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                {images.map((img, index) => (
                  <div key={index} className="relative group">
                    <img
                      src={img}
                      alt={`Product ${index + 1}`}
                      className="w-full h-32 object-cover rounded-lg border-2 border-neutral-200"
                    />
                    <button
                      type="button"
                      onClick={() => handleImageRemove(index)}
                      className="absolute top-2 right-2 w-8 h-8 bg-error text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <X className="w-4 h-4" />
                    </button>
                  </div>
                ))}

                {images.length < 4 && (
                  <button
                    type="button"
                    onClick={handleImageAdd}
                    className="h-32 border-2 border-dashed border-neutral-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors flex flex-col items-center justify-center gap-2"
                  >
                    <Upload className="w-6 h-6 text-neutral-400" />
                    <span className="text-neutral-600" style={{ fontSize: "0.875rem" }}>Upload Photo</span>
                  </button>
                )}
              </div>

              <p className="text-neutral-500" style={{ fontSize: "0.875rem" }}>
                📸 Add up to 4 clear photos of your product. Good photos help buyers!
              </p>
            </div>

            <div>
              <label htmlFor="description" className="block mb-2">
                Description
              </label>
              <textarea
                id="description"
                value={formData.description}
                onChange={(e) => handleChange("description", e.target.value)}
                placeholder="Add details about your product: freshness, organic/inorganic, harvest date, etc."
                rows={4}
                className="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary resize-none"
              />
            </div>

            <div className="bg-primary/10 p-4 rounded-lg">
              <h4 className="mb-2">💡 Tips for Better Sales:</h4>
              <ul className="space-y-1 text-neutral-700" style={{ fontSize: "0.875rem" }}>
                <li>• Use clear, well-lit photos</li>
                <li>• Set competitive prices</li>
                <li>• Update stock regularly</li>
                <li>• Mention if organic or pesticide-free</li>
              </ul>
            </div>

            <div className="flex gap-4 pt-4">
              <button
                type="button"
                onClick={() => navigate("buyer-dashboard")}
                className="flex-1 py-3 border-2 border-neutral-300 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                className="flex-1 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
              >
                Post Product
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}


