# AgriConnect - Quick Start Guide

🚀 **Get AgriConnect running in 5 minutes!**

---

## For Developers

### Current React/TypeScript Version (This Repository)

**What you have**: A fully functional React prototype with all 21 pages implemented.

#### Run Locally

```bash
# 1. No installation needed - this is Figma Make environment
# 2. The app is already running!
# 3. Navigate through the pages using the interface
```

#### Test the Application

1. **Start at Landing Page** - Click through the hero section
2. **Browse Marketplace** - View products, use filters
3. **Test Authentication**:
   - Click "Login"
   - Try different user roles (farmer/buyer/admin)
   - Use demo credentials from README
4. **Farmer Flow**:
   - Login as farmer
   - View dashboard
   - Add product
   - Manage inventory
5. **Buyer Flow**:
   - Login as buyer
   - Browse products
   - Add to cart
   - Proceed to checkout
6. **Admin Flow**:
   - Login as admin
   - Manage users
   - Moderate products
   - Post announcements

#### Key Files to Understand

```
/App.tsx                    → Main routing and state
/components/Layout.tsx      → Navigation and footer
/styles/globals.css         → Design system
/services/dataService.ts    → Mock backend (local storage)
```

---

## For Conversion to Production

### Option 1: Deploy as Static Site (Easiest)

**Best for**: Quick demos, client previews

```bash
# 1. Build the React app
npm run build

# 2. Upload 'dist' folder to any static host:
#    - Netlify (drag and drop)
#    - Vercel (connect GitHub)
#    - GitHub Pages
#    - Any web server
```

**Limitations**:
- No real backend
- Data doesn't persist between sessions
- No real authentication
- Mock data only

---

### Option 2: Convert to CodeIgniter 4 (Recommended for Production)

**Best for**: Full production deployment, real users

**Follow these guides in order:**

#### Step 1: Read the Documentation (30 minutes)
- 📖 [README.md](./README.md) - Project overview
- 📖 [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md) - Full conversion guide
- 📖 [CONVERSION_CHECKLIST.md](./CONVERSION_CHECKLIST.md) - Page-by-page tasks

#### Step 2: Set Up Environment (1 hour)
1. **Install CodeIgniter 4**
   ```bash
   composer create-project codeigniter4/appstarter agriconnect-backend
   cd agriconnect-backend
   ```

2. **Set up database**
   - Create MySQL database
   - Run schema from DEPLOYMENT_GUIDE.md
   - Configure database connection

3. **Copy assets**
   - Copy `/styles/globals.css` to `public/assets/css/`
   - Set up Tailwind CSS (CDN or compiled)

#### Step 3: Create Models (2 hours)
- UserModel
- ProductModel
- OrderModel
- MessageModel
- AnnouncementModel

See DEPLOYMENT_GUIDE.md for complete code examples.

#### Step 4: Create Controllers (3 hours)
- AuthController (login, register, logout)
- Farmer (dashboard, products, orders)
- Buyer (orders, cart)
- Marketplace
- Admin

#### Step 5: Convert Views (4-6 hours)
Convert each React component to PHP view:
- Use CONVERSION_CHECKLIST.md as reference
- Copy HTML structure
- Replace React components with PHP
- Add forms with CSRF tokens

#### Step 6: Test & Deploy (2-4 hours)
- Test all user flows
- Security audit
- Deploy to Hostinger
- Configure SSL

**Total estimated time**: 15-20 hours

---

### Option 3: HTML Export (Quick Reference)

**Best for**: Sharing designs with clients, creating templates

**Follow**: [HTML_EXPORT_GUIDE.md](./HTML_EXPORT_GUIDE.md)

1. Copy base HTML template
2. Replace content sections
3. Add page-specific code
4. Save as .html files
5. Open in browser

---

## For Client/Stakeholder Review

### View the Current Prototype

**What works:**
- ✅ All 21 pages are functional
- ✅ Navigation between pages
- ✅ Forms (no actual submission)
- ✅ Product filtering and search
- ✅ Cart functionality (local storage)
- ✅ Mobile responsive design
- ✅ Filipino farmer-friendly UI

**What's simulated:**
- ⚠️ Login (no real authentication)
- ⚠️ Database (uses local storage)
- ⚠️ Image uploads (mock)
- ⚠️ Order processing (simulated)
- ⚠️ Messaging (mock data)

### Navigation Map

```
Landing Page (/)
├── Login (/auth/login)
│   ├── Farmer Dashboard → Add Product → Inventory → Orders
│   ├── Buyer → Marketplace → Product Detail → Cart → Checkout → Order History
│   └── Admin → Dashboard → Users → Products → Announcements
├── Marketplace
│   └── Product Details
├── Weather
├── Announcements
├── Forum
└── Messages (requires login)
```

### Testing Checklist for Review

- [ ] Mobile view (resize browser to 375px width)
- [ ] Tablet view (768px)
- [ ] Desktop view (1280px+)
- [ ] Test all buttons and links
- [ ] Test forms (they won't submit to server)
- [ ] Test search and filters
- [ ] Test cart functionality
- [ ] Navigate all user journeys
- [ ] Check color scheme and branding
- [ ] Verify text is readable (especially on mobile)

---

## For Hostinger Deployment

### Prerequisites

1. **Hostinger Account** with:
   - PHP 8.0+ support
   - MySQL database
   - SSH access (recommended)
   - SSL certificate (free with Hostinger)

2. **Domain Name** (optional but recommended)
   - e.g., agriconnect.ph

### Quick Deploy Steps

#### 1. Prepare Files Locally

```bash
# Build CodeIgniter 4 version following DEPLOYMENT_GUIDE.md
# You should have:
- app/ (controllers, models, views)
- public/ (index.php, assets)
- writable/ (cache, logs)
- .env (configured)
```

#### 2. Upload to Hostinger

**Option A: File Manager**
1. Login to Hostinger control panel
2. Go to File Manager
3. Navigate to `public_html`
4. Upload all files
5. Extract if needed

**Option B: FTP/SFTP**
```bash
# Using FileZilla or similar
Host: ftp.yourdomain.com
Username: [your FTP username]
Password: [your FTP password]

# Upload all files to public_html
```

**Option C: Git (Best Practice)**
```bash
# SSH into Hostinger
ssh username@yourdomain.com

# Clone repository
cd public_html
git clone https://github.com/yourusername/agriconnect.git .

# Install dependencies
composer install

# Set permissions
chmod 755 writable/
```

#### 3. Configure Database

1. **Create Database** in Hostinger panel
2. **Import Schema**
   - Upload SQL file
   - Or use phpMyAdmin
3. **Update .env**
   ```
   database.default.hostname = localhost
   database.default.database = your_db_name
   database.default.username = your_db_user
   database.default.password = your_db_password
   ```

#### 4. Set Environment

```bash
# In .env file
CI_ENVIRONMENT = production

# Set base URL
app.baseURL = 'https://yourdomain.com/'
```

#### 5. Enable SSL

1. Go to Hostinger control panel
2. SSL → Install SSL Certificate
3. Choose "Free SSL"
4. Wait for activation (5-15 minutes)

#### 6. Test

1. Visit your domain
2. Test all functionality
3. Check error logs if issues occur
4. Monitor for a few days

---

## Common Issues & Solutions

### Issue: "404 Page Not Found"

**Solution**:
```apache
# Check .htaccess in public folder
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### Issue: "Database Connection Failed"

**Solution**:
- Check database credentials in .env
- Verify database exists
- Check database user permissions
- Test connection with phpMyAdmin

### Issue: "Styles Not Loading"

**Solution**:
- Check asset paths are correct
- Verify public folder is web root
- Check file permissions (644 for files, 755 for folders)
- Clear browser cache

### Issue: "Images Not Uploading"

**Solution**:
```bash
# Set proper permissions
chmod 755 public/uploads/
chmod 755 public/uploads/products/

# Check upload settings in php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Issue: "Session Not Working"

**Solution**:
```bash
# Check writable/session permissions
chmod 755 writable/session/

# In .env, set session driver
session.driver = CodeIgniter\Session\Handlers\FileHandler
session.savePath = writable/session
```

---

## Performance Optimization

### For Low Bandwidth (Rural Internet)

1. **Optimize Images**
   - Resize to max 800px width
   - Compress to 70-80% quality
   - Use WebP format
   - Implement lazy loading

2. **Minify Assets**
   - Minify CSS and JS
   - Combine files where possible
   - Use Gzip compression

3. **Enable Caching**
   ```apache
   # In .htaccess
   <IfModule mod_expires.c>
     ExpiresActive On
     ExpiresByType image/jpg "access plus 1 year"
     ExpiresByType image/png "access plus 1 year"
     ExpiresByType text/css "access plus 1 month"
     ExpiresByType text/javascript "access plus 1 month"
   </IfModule>
   ```

4. **Database Optimization**
   - Index frequently queried columns
   - Use query caching
   - Paginate large result sets

---

## Next Steps

### After Initial Deployment

1. **Week 1: Monitoring**
   - Check error logs daily
   - Monitor user feedback
   - Fix critical bugs

2. **Week 2-4: Optimization**
   - Optimize slow queries
   - Improve page load times
   - Enhance mobile experience

3. **Month 2: Features**
   - Add payment gateway
   - Implement SMS notifications
   - Add reviews and ratings

4. **Month 3+: Scaling**
   - Consider VPS upgrade if traffic increases
   - Add CDN for static assets
   - Implement caching layers

---

## Support Resources

### Documentation
- ✅ [README.md](./README.md) - Project overview
- ✅ [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md) - Full deployment
- ✅ [CONVERSION_CHECKLIST.md](./CONVERSION_CHECKLIST.md) - Task list
- ✅ [HTML_EXPORT_GUIDE.md](./HTML_EXPORT_GUIDE.md) - HTML templates

### External Resources
- [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Hostinger Knowledge Base](https://support.hostinger.com/)
- [PHP Documentation](https://www.php.net/docs.php)

### Community
- CodeIgniter Forum
- Stack Overflow
- GitHub Issues

---

## Success Checklist

Before launching to users:

- [ ] All pages load correctly
- [ ] Database is populated with initial data
- [ ] Authentication works (register, login, logout)
- [ ] Products can be added/edited/deleted
- [ ] Orders can be placed and tracked
- [ ] Messages can be sent
- [ ] Admin panel is functional
- [ ] Mobile responsive on all pages
- [ ] Images load properly
- [ ] Forms validate correctly
- [ ] SSL certificate is active
- [ ] Backups are configured
- [ ] Error logging is enabled
- [ ] Performance is acceptable on slow connections
- [ ] Security audit completed
- [ ] User testing completed

---

**Ready to Launch? 🚀**

Once all checklist items are complete:
1. Announce to farmers and cooperatives
2. Provide training/onboarding
3. Monitor closely for first week
4. Gather feedback
5. Iterate and improve

---

**Questions? Issues?**

Contact: info@agriconnect.ph

---

**Good luck with your deployment! 🌾**
