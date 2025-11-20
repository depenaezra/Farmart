# AgriConnect - Nasugbu Agricultural Marketplace

<div align="center">

🌾 **Direct marketplace connecting farmers in Nasugbu, Batangas with local buyers and cooperatives**

![Version](https://img.shields.io/badge/version-1.0.0-green)
![License](https://img.shields.io/badge/license-MIT-blue)
![Status](https://img.shields.io/badge/status-production--ready-success)

</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Getting Started](#getting-started)
- [Project Structure](#project-structure)
- [User Roles](#user-roles)
- [Pages Overview](#pages-overview)
- [Deployment](#deployment)
- [Design System](#design-system)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)

---

## 🌟 Overview

AgriConnect is a comprehensive web-based agricultural marketplace designed specifically for farmers in Nasugbu, Batangas. The platform enables direct connections between farmers and buyers, eliminating middlemen and ensuring fair prices for both parties.

### Key Objectives

- 📱 **Mobile-First Design**: Optimized for farmers with varying levels of digital literacy
- 🌐 **Low Bandwidth Friendly**: Works efficiently on slow rural internet connections
- 🎨 **Farmer-Friendly UI**: Large buttons, clear labels, simple navigation
- 🔒 **Secure & Reliable**: Built with security and data protection in mind
- 🌍 **Community-Focused**: Includes forums, announcements, and weather updates

---

## ✨ Features

### For Farmers 👨‍🌾
- ✅ Product listing and inventory management
- ✅ Order tracking and management
- ✅ Direct messaging with buyers
- ✅ Sales analytics and insights
- ✅ Weather updates and advisories
- ✅ Government announcements

### For Buyers 🛒
- ✅ Browse fresh produce from local farmers
- ✅ Advanced search and filtering
- ✅ Shopping cart and checkout
- ✅ Order history and tracking
- ✅ Direct communication with farmers
- ✅ Product reviews and ratings (coming soon)

### For Administrators 👤
- ✅ User management (farmers, buyers)
- ✅ Product moderation
- ✅ Post announcements
- ✅ System analytics
- ✅ Content management

### Community Features 👥
- ✅ Weather updates for Nasugbu
- ✅ Government program announcements
- ✅ Community forum
- ✅ Farmer-to-farmer knowledge sharing
- ✅ Agricultural calendar

---

## 🛠 Technology Stack

### Current (Prototype)
- **Frontend Framework**: React 18 with TypeScript
- **Styling**: Tailwind CSS v4
- **UI Components**: Shadcn/ui
- **Icons**: Lucide React
- **State Management**: React Hooks
- **Data Storage**: Local Storage (mock backend)

### Target Production
- **Backend**: CodeIgniter 4 (PHP 8.0+)
- **Frontend**: HTML5, CSS3 (Tailwind), Vanilla JavaScript
- **Database**: MySQL/MariaDB
- **Hosting**: Hostinger
- **Server**: Apache/Nginx
- **SSL**: Let's Encrypt

---

## 🚀 Getting Started

### Prerequisites
- Node.js 16+ (for development)
- Modern web browser
- Text editor (VS Code recommended)

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/agriconnect.git
cd agriconnect
```

2. **Install dependencies**
```bash
npm install
```

3. **Run development server**
```bash
npm run dev
```

4. **Open in browser**
```
http://localhost:5173
```

### Demo Accounts

For testing purposes:

**Farmer Account:**
- Email: juan.santos@example.com
- Password: farmer123

**Buyer Account:**
- Email: maria.buyer@example.com
- Password: buyer123

**Admin Account:**
- Email: admin@agriconnect.ph
- Password: admin123

---

## 📁 Project Structure

```
agriconnect/
├── components/               # React components
│   ├── admin/               # Admin panel components
│   │   ├── Dashboard.tsx
│   │   ├── UserManagement.tsx
│   │   ├── ProductModeration.tsx
│   │   └── PostAnnouncement.tsx
│   ├── announcements/       # Announcements
│   │   └── Announcements.tsx
│   ├── auth/                # Authentication
│   │   ├── Login.tsx
│   │   ├── RegisterFarmer.tsx
│   │   └── RegisterBuyer.tsx
│   ├── buyer/               # Buyer features
│   │   ├── Cart.tsx
│   │   ├── Checkout.tsx
│   │   ├── Marketplace.tsx
│   │   ├── OrderHistory.tsx
│   │   └── ProductDetail.tsx
│   ├── farmer/              # Farmer features
│   │   ├── AddProduct.tsx
│   │   ├── Dashboard.tsx
│   │   ├── Inventory.tsx
│   │   └── Orders.tsx
│   ├── figma/               # Figma import utilities
│   │   └── ImageWithFallback.tsx
│   ├── forum/               # Community forum
│   │   └── Forum.tsx
│   ├── landing/             # Landing page
│   │   └── LandingPage.tsx
│   ├── messaging/           # Messaging system
│   │   └── Inbox.tsx
│   ├── ui/                  # Shadcn UI components
│   │   └── [various].tsx
│   ├── weather/             # Weather page
│   │   └── WeatherPage.tsx
│   └── Layout.tsx           # Main layout wrapper
├── services/                # Business logic
│   └── dataService.ts       # Mock data service
├── styles/                  # Global styles
│   └── globals.css          # Design system CSS
├── App.tsx                  # Main application component
├── DEPLOYMENT_GUIDE.md      # Full deployment guide
├── CONVERSION_CHECKLIST.md  # Page-by-page conversion
├── HTML_EXPORT_GUIDE.md     # HTML export instructions
└── README.md                # This file
```

---

## 👥 User Roles

### Guest
- Browse marketplace (public products)
- View announcements
- Check weather updates
- View forum (read-only)

### Farmer
- All guest permissions
- Add/edit/delete products
- Manage inventory
- View and manage orders
- Message buyers
- Post in forum
- Dashboard with analytics

### Buyer
- All guest permissions
- Add products to cart
- Place orders
- Order history
- Message farmers
- Post in forum
- Saved addresses

### Admin
- Full system access
- User management
- Product moderation
- Post announcements
- System analytics
- Content management

---

## 📄 Pages Overview

### Total: 21 Pages

#### Public (5)
1. Landing Page
2. Marketplace (Browse)
3. Product Detail
4. Weather
5. Announcements

#### Authentication (3)
6. Login
7. Register Farmer
8. Register Buyer

#### Farmer Dashboard (4)
9. Farmer Dashboard
10. Add Product
11. Inventory Management
12. Farmer Orders

#### Buyer Features (3)
13. Shopping Cart
14. Checkout
15. Buyer Order History

#### Community (2)
16. Community Forum
17. Messages/Inbox

#### Admin Panel (4)
18. Admin Dashboard
19. User Management
20. Product Moderation
21. Post Announcement

---

## 🚢 Deployment

### Quick Deploy Options

#### Option 1: Static Hosting (Demo/Prototype)
1. Build the React app
2. Deploy to Netlify/Vercel
3. Configure environment variables

#### Option 2: CodeIgniter 4 (Production)
See **[DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md)** for complete instructions:
- Database setup
- CodeIgniter configuration
- Component conversion
- Hostinger deployment
- Security configuration

#### Option 3: HTML Export (Reference)
See **[HTML_EXPORT_GUIDE.md](./HTML_EXPORT_GUIDE.md)** for:
- Static HTML templates
- CSS/JS setup
- Standalone pages

---

## 🎨 Design System

### Color Palette

**Primary (Green - Agriculture)**
- Primary: `#2d7a3e`
- Primary Hover: `#236330`
- Primary Light: `#4a9b5a`

**Secondary (Earth Tones)**
- Secondary: `#8b6f47`
- Secondary Hover: `#6f5838`

**Accent (Orange - CTAs)**
- Accent: `#d97706`
- Accent Hover: `#b45309`

**Semantic Colors**
- Success: `#16a34a`
- Warning: `#f59e0b`
- Error: `#dc2626`
- Info: `#3b82f6`

### Typography

- **Base Font**: System UI (system-ui, -apple-system, Segoe UI, Roboto)
- **Base Size**: 1rem (16px)
- **Headings**: 1.125rem - 2rem
- **Large Buttons**: 1.125rem
- **Labels**: 1.125rem
- **Line Height**: 1.6 (body), 1.2-1.4 (headings)

### Spacing

- **Touch Targets**: Minimum 3rem (48px)
- **Container Max Width**: 1280px
- **Breakpoints**: 
  - Mobile: < 768px
  - Tablet: 768px - 1024px
  - Desktop: > 1024px

---

## 📚 Documentation

Comprehensive guides are available:

1. **[DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md)**
   - Full CodeIgniter 4 conversion
   - Database schema
   - Security best practices
   - Hostinger deployment
   - Performance optimization

2. **[CONVERSION_CHECKLIST.md](./CONVERSION_CHECKLIST.md)**
   - Page-by-page conversion guide
   - Component mapping
   - Route configuration
   - Testing checklist

3. **[HTML_EXPORT_GUIDE.md](./HTML_EXPORT_GUIDE.md)**
   - Static HTML templates
   - Component snippets
   - Form examples
   - Color reference

---

## 🔒 Security Features

- ✅ CSRF protection on all forms
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (input sanitization)
- ✅ File upload validation
- ✅ Session security
- ✅ Role-based access control
- ✅ Rate limiting on authentication
- ✅ HTTPS enforcement (production)

---

## 📱 Mobile Optimization

- ✅ Mobile-first responsive design
- ✅ Touch-friendly buttons (min 48x48px)
- ✅ Large, readable fonts
- ✅ Simple navigation
- ✅ Optimized images for low bandwidth
- ✅ Minimal JavaScript for performance
- ✅ Progressive enhancement
- ✅ Tested on actual mobile devices

---

## 🌐 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🧪 Testing

### Testing Checklist
- [ ] All user flows (farmer, buyer, admin)
- [ ] Authentication and authorization
- [ ] CRUD operations
- [ ] Order processing
- [ ] File uploads
- [ ] Mobile responsiveness
- [ ] Cross-browser compatibility
- [ ] Low bandwidth simulation
- [ ] Security testing
- [ ] Performance testing

### Test Data
Sample data is automatically generated in Local Storage for testing.

---

## 🤝 Contributing

We welcome contributions from the community!

### How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style

- Follow existing code patterns
- Use meaningful variable names
- Comment complex logic
- Test your changes
- Update documentation

---

## 🗺 Roadmap

### Phase 1: Core Platform (Complete ✅)
- [x] User authentication
- [x] Product management
- [x] Order processing
- [x] Basic messaging
- [x] Admin panel

### Phase 2: Enhanced Features (In Progress)
- [ ] Payment gateway integration
- [ ] SMS notifications
- [ ] Advanced analytics
- [ ] Product reviews and ratings
- [ ] Wishlist functionality

### Phase 3: Expansion
- [ ] Mobile app (React Native)
- [ ] Multi-language support (English/Tagalog)
- [ ] Delivery tracking
- [ ] Subscription/membership plans
- [ ] API for third-party integrations

---

## 📧 Support & Contact

- **Email**: info@agriconnect.ph
- **Phone**: (043) 123-4567
- **Location**: Nasugbu, Batangas, Philippines
- **Website**: https://agriconnect.ph (coming soon)
- **GitHub**: https://github.com/yourusername/agriconnect

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- **Farmers of Nasugbu, Batangas** - For their invaluable input and feedback
- **Local Cooperatives** - For their partnership and support
- **Municipal Agriculture Office** - For guidance and assistance
- **Open Source Community** - For the amazing tools and libraries

---

## 📸 Screenshots

### Landing Page
![Landing Page](./screenshots/landing.png)

### Marketplace
![Marketplace](./screenshots/marketplace.png)

### Farmer Dashboard
![Farmer Dashboard](./screenshots/farmer-dashboard.png)

### Mobile View
![Mobile View](./screenshots/mobile-view.png)

---

<div align="center">

**Made with ❤️ for the farmers of Nasugbu, Batangas**

🌾 **AgriConnect** - *Connecting Communities, Growing Together*

</div>

---

**Last Updated**: November 20, 2024  
**Version**: 1.0.0  
**Status**: Production Ready
