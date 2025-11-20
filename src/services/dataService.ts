/**
 * AgriConnect Data Service
 * Mock data service that simulates backend API calls
 * In production, replace these with actual API calls to CodeIgniter 4 backend
 */

// Product data types
export interface Product {
  id: string;
  name: string;
  price: number;
  unit: string;
  farmer: string;
  farmerId: string;
  cooperative: string;
  stock: string;
  stockQuantity: number;
  category: string;
  location: string;
  description: string;
  imageUrl: string;
  createdAt: string;
  status: 'available' | 'out-of-stock' | 'pending';
}

export interface User {
  id: string;
  name: string;
  email: string;
  role: 'farmer' | 'buyer' | 'admin';
  phone: string;
  location: string;
  cooperative?: string;
  createdAt: string;
}

export interface Order {
  id: string;
  buyerId: string;
  buyerName: string;
  farmerId: string;
  farmerName: string;
  productId: string;
  productName: string;
  quantity: number;
  unit: string;
  totalPrice: number;
  status: 'pending' | 'confirmed' | 'processing' | 'completed' | 'cancelled';
  deliveryAddress: string;
  createdAt: string;
  updatedAt: string;
}

export interface Message {
  id: string;
  senderId: string;
  senderName: string;
  receiverId: string;
  receiverName: string;
  subject: string;
  message: string;
  read: boolean;
  createdAt: string;
}

export interface Announcement {
  id: string;
  title: string;
  content: string;
  category: 'weather' | 'government' | 'market' | 'general';
  priority: 'low' | 'medium' | 'high';
  createdAt: string;
  createdBy: string;
}

// Local storage keys
const STORAGE_KEYS = {
  PRODUCTS: 'agriconnect_products',
  USERS: 'agriconnect_users',
  ORDERS: 'agriconnect_orders',
  MESSAGES: 'agriconnect_messages',
  ANNOUNCEMENTS: 'agriconnect_announcements',
  CURRENT_USER: 'agriconnect_current_user',
  CART: 'agriconnect_cart'
};

// Initialize with sample data
const initializeData = () => {
  // Sample Products
  const sampleProducts: Product[] = [
    {
      id: '1',
      name: 'Fresh Tomatoes',
      price: 80,
      unit: 'kilo',
      farmer: 'Juan Santos',
      farmerId: 'farmer-1',
      cooperative: 'Nasugbu Farmers Coop',
      stock: '50 kg',
      stockQuantity: 50,
      category: 'vegetables',
      location: 'Brgy. Aga, Nasugbu',
      description: 'Farm-fresh tomatoes, perfect for cooking. Harvested daily.',
      imageUrl: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    },
    {
      id: '2',
      name: 'Organic Lettuce',
      price: 60,
      unit: 'kilo',
      farmer: 'Maria Cruz',
      farmerId: 'farmer-2',
      cooperative: 'Green Valley Coop',
      stock: '30 kg',
      stockQuantity: 30,
      category: 'vegetables',
      location: 'Brgy. Wawa, Nasugbu',
      description: 'Crispy organic lettuce grown without pesticides.',
      imageUrl: 'https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    },
    {
      id: '3',
      name: 'Native Corn',
      price: 45,
      unit: 'kilo',
      farmer: 'Pedro Reyes',
      farmerId: 'farmer-3',
      cooperative: 'Batangas Corn Growers',
      stock: '100 kg',
      stockQuantity: 100,
      category: 'grains',
      location: 'Brgy. Lumbangan, Nasugbu',
      description: 'Sweet native corn, freshly harvested.',
      imageUrl: 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    },
    {
      id: '4',
      name: 'Banana Lakatan',
      price: 70,
      unit: 'kilo',
      farmer: 'Rosa Garcia',
      farmerId: 'farmer-4',
      cooperative: 'Nasugbu Farmers Coop',
      stock: '80 kg',
      stockQuantity: 80,
      category: 'fruits',
      location: 'Brgy. Poblacion, Nasugbu',
      description: 'Premium lakatan bananas, naturally ripened.',
      imageUrl: 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    },
    {
      id: '5',
      name: 'Eggplant',
      price: 55,
      unit: 'kilo',
      farmer: 'Juan Santos',
      farmerId: 'farmer-1',
      cooperative: 'Nasugbu Farmers Coop',
      stock: '40 kg',
      stockQuantity: 40,
      category: 'vegetables',
      location: 'Brgy. Aga, Nasugbu',
      description: 'Fresh eggplants for your favorite dishes.',
      imageUrl: 'https://images.unsplash.com/photo-1659261200833-ec8761558af7?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    },
    {
      id: '6',
      name: 'Pineapple',
      price: 90,
      unit: 'piece',
      farmer: 'Ana Bautista',
      farmerId: 'farmer-5',
      cooperative: 'Green Valley Coop',
      stock: '25 pieces',
      stockQuantity: 25,
      category: 'fruits',
      location: 'Brgy. Mataas na Pulo, Nasugbu',
      description: 'Sweet and juicy pineapples from local farms.',
      imageUrl: 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=400',
      createdAt: new Date().toISOString(),
      status: 'available'
    }
  ];

  // Sample Users
  const sampleUsers: User[] = [
    {
      id: 'farmer-1',
      name: 'Juan Santos',
      email: 'juan.santos@example.com',
      role: 'farmer',
      phone: '0917-123-4567',
      location: 'Brgy. Aga, Nasugbu',
      cooperative: 'Nasugbu Farmers Coop',
      createdAt: new Date().toISOString()
    },
    {
      id: 'buyer-1',
      name: 'Maria Buyer',
      email: 'maria.buyer@example.com',
      role: 'buyer',
      phone: '0918-234-5678',
      location: 'Nasugbu Town Center',
      createdAt: new Date().toISOString()
    },
    {
      id: 'admin-1',
      name: 'Admin User',
      email: 'admin@agriconnect.ph',
      role: 'admin',
      phone: '0919-345-6789',
      location: 'Nasugbu',
      createdAt: new Date().toISOString()
    }
  ];

  // Sample Announcements
  const sampleAnnouncements: Announcement[] = [
    {
      id: '1',
      title: 'Weather Alert: Heavy Rain Expected',
      content: 'PAGASA warns of heavy rain this weekend. Please secure your crops and prepare drainage systems.',
      category: 'weather',
      priority: 'high',
      createdAt: new Date().toISOString(),
      createdBy: 'Admin'
    },
    {
      id: '2',
      title: 'New Government Subsidy Program',
      content: 'DA announces new subsidy program for small-scale farmers. Registration starts next week at the Municipal Agriculture Office.',
      category: 'government',
      priority: 'medium',
      createdAt: new Date().toISOString(),
      createdBy: 'Admin'
    },
    {
      id: '3',
      title: 'Market Price Update',
      content: 'Vegetable prices remain stable. Tomatoes: ₱70-85/kg, Lettuce: ₱55-65/kg, Eggplant: ₱50-60/kg',
      category: 'market',
      priority: 'low',
      createdAt: new Date().toISOString(),
      createdBy: 'Admin'
    }
  ];

  // Initialize local storage if empty
  if (!localStorage.getItem(STORAGE_KEYS.PRODUCTS)) {
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(sampleProducts));
  }
  if (!localStorage.getItem(STORAGE_KEYS.USERS)) {
    localStorage.setItem(STORAGE_KEYS.USERS, JSON.stringify(sampleUsers));
  }
  if (!localStorage.getItem(STORAGE_KEYS.ORDERS)) {
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify([]));
  }
  if (!localStorage.getItem(STORAGE_KEYS.MESSAGES)) {
    localStorage.setItem(STORAGE_KEYS.MESSAGES, JSON.stringify([]));
  }
  if (!localStorage.getItem(STORAGE_KEYS.ANNOUNCEMENTS)) {
    localStorage.setItem(STORAGE_KEYS.ANNOUNCEMENTS, JSON.stringify(sampleAnnouncements));
  }
};

// Data Service Functions
export const dataService = {
  // Initialize data
  init: () => {
    initializeData();
  },

  // Products
  getAllProducts: (): Product[] => {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    return data ? JSON.parse(data) : [];
  },

  getProductById: (id: string): Product | null => {
    const products = dataService.getAllProducts();
    return products.find(p => p.id === id) || null;
  },

  getProductsByFarmer: (farmerId: string): Product[] => {
    const products = dataService.getAllProducts();
    return products.filter(p => p.farmerId === farmerId);
  },

  addProduct: (product: Omit<Product, 'id' | 'createdAt'>): Product => {
    const products = dataService.getAllProducts();
    const newProduct: Product = {
      ...product,
      id: `prod-${Date.now()}`,
      createdAt: new Date().toISOString()
    };
    products.push(newProduct);
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(products));
    return newProduct;
  },

  updateProduct: (id: string, updates: Partial<Product>): Product | null => {
    const products = dataService.getAllProducts();
    const index = products.findIndex(p => p.id === id);
    if (index === -1) return null;
    
    products[index] = { ...products[index], ...updates };
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(products));
    return products[index];
  },

  deleteProduct: (id: string): boolean => {
    const products = dataService.getAllProducts();
    const filtered = products.filter(p => p.id !== id);
    if (filtered.length === products.length) return false;
    
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(filtered));
    return true;
  },

  // Orders
  getAllOrders: (): Order[] => {
    const data = localStorage.getItem(STORAGE_KEYS.ORDERS);
    return data ? JSON.parse(data) : [];
  },

  getOrdersByBuyer: (buyerId: string): Order[] => {
    const orders = dataService.getAllOrders();
    return orders.filter(o => o.buyerId === buyerId);
  },

  getOrdersByFarmer: (farmerId: string): Order[] => {
    const orders = dataService.getAllOrders();
    return orders.filter(o => o.farmerId === farmerId);
  },

  createOrder: (order: Omit<Order, 'id' | 'createdAt' | 'updatedAt'>): Order => {
    const orders = dataService.getAllOrders();
    const newOrder: Order = {
      ...order,
      id: `ORD-${Date.now()}`,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    };
    orders.push(newOrder);
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(orders));
    return newOrder;
  },

  updateOrderStatus: (id: string, status: Order['status']): Order | null => {
    const orders = dataService.getAllOrders();
    const index = orders.findIndex(o => o.id === id);
    if (index === -1) return null;
    
    orders[index].status = status;
    orders[index].updatedAt = new Date().toISOString();
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(orders));
    return orders[index];
  },

  // Messages
  getAllMessages: (): Message[] => {
    const data = localStorage.getItem(STORAGE_KEYS.MESSAGES);
    return data ? JSON.parse(data) : [];
  },

  getMessagesByUser: (userId: string): Message[] => {
    const messages = dataService.getAllMessages();
    return messages.filter(m => m.senderId === userId || m.receiverId === userId);
  },

  sendMessage: (message: Omit<Message, 'id' | 'createdAt' | 'read'>): Message => {
    const messages = dataService.getAllMessages();
    const newMessage: Message = {
      ...message,
      id: `msg-${Date.now()}`,
      read: false,
      createdAt: new Date().toISOString()
    };
    messages.push(newMessage);
    localStorage.setItem(STORAGE_KEYS.MESSAGES, JSON.stringify(messages));
    return newMessage;
  },

  markMessageAsRead: (id: string): boolean => {
    const messages = dataService.getAllMessages();
    const index = messages.findIndex(m => m.id === id);
    if (index === -1) return false;
    
    messages[index].read = true;
    localStorage.setItem(STORAGE_KEYS.MESSAGES, JSON.stringify(messages));
    return true;
  },

  // Announcements
  getAllAnnouncements: (): Announcement[] => {
    const data = localStorage.getItem(STORAGE_KEYS.ANNOUNCEMENTS);
    return data ? JSON.parse(data) : [];
  },

  createAnnouncement: (announcement: Omit<Announcement, 'id' | 'createdAt'>): Announcement => {
    const announcements = dataService.getAllAnnouncements();
    const newAnnouncement: Announcement = {
      ...announcement,
      id: `ann-${Date.now()}`,
      createdAt: new Date().toISOString()
    };
    announcements.unshift(newAnnouncement); // Add to beginning
    localStorage.setItem(STORAGE_KEYS.ANNOUNCEMENTS, JSON.stringify(announcements));
    return newAnnouncement;
  },

  // Users
  getAllUsers: (): User[] => {
    const data = localStorage.getItem(STORAGE_KEYS.USERS);
    return data ? JSON.parse(data) : [];
  },

  getUserById: (id: string): User | null => {
    const users = dataService.getAllUsers();
    return users.find(u => u.id === id) || null;
  },

  createUser: (user: Omit<User, 'id' | 'createdAt'>): User => {
    const users = dataService.getAllUsers();
    const newUser: User = {
      ...user,
      id: `user-${Date.now()}`,
      createdAt: new Date().toISOString()
    };
    users.push(newUser);
    localStorage.setItem(STORAGE_KEYS.USERS, JSON.stringify(users));
    return newUser;
  },

  // Authentication (mock)
  login: (email: string, password: string): User | null => {
    // In production, this would make an API call
    // For now, we'll just find user by email
    const users = dataService.getAllUsers();
    const user = users.find(u => u.email === email);
    if (user) {
      localStorage.setItem(STORAGE_KEYS.CURRENT_USER, JSON.stringify(user));
      return user;
    }
    return null;
  },

  getCurrentUser: (): User | null => {
    const data = localStorage.getItem(STORAGE_KEYS.CURRENT_USER);
    return data ? JSON.parse(data) : null;
  },

  logout: () => {
    localStorage.removeItem(STORAGE_KEYS.CURRENT_USER);
  },

  // Cart
  getCart: (): any[] => {
    const data = localStorage.getItem(STORAGE_KEYS.CART);
    return data ? JSON.parse(data) : [];
  },

  saveCart: (cart: any[]) => {
    localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify(cart));
  },

  clearCart: () => {
    localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify([]));
  }
};

// Initialize data on module load
dataService.init();
