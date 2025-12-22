# GOOGLE OAUTH SETUP GUIDE

## 📋 Complete Setup Instructions

### Step 1: Create Google Cloud Project

1. **Visit Google Cloud Console**
   - Go to: https://console.cloud.google.com/

2. **Create New Project**
   - Click "Select a project" → "New Project"
   - Name: "HISurabaya"
   - Click "Create"

3. **Enable Google+ API**
   - Go to: "APIs & Services" → "Library"
   - Search for: "Google+ API"
   - Click "Enable"

### Step 2: Create OAuth 2.0 Credentials

1. **Go to Credentials**
   - "APIs & Services" → "Credentials"

2. **Configure OAuth Consent Screen**
   - Click "Configure Consent Screen"
   - Choose "External" (for testing)
   - Fill in:
     - App name: HISurabaya
     - User support email: your email
     - Developer contact: your email
   - Click "Save and Continue"
   - Skip "Scopes" → "Save and Continue"
   - Add test users (your Gmail) → "Save and Continue"

3. **Create OAuth Client ID**
   - Click "Create Credentials" → "OAuth client ID"
   - Application type: "Web application"
   - Name: "HISurabaya Web Client"
   - Authorized redirect URIs:
     ```
     http://localhost:8000/auth/google/callback
     ```
   - Click "Create"

4. **Copy Credentials**
   - You'll get:
     - Client ID: `xxxxxxxxxx.apps.googleusercontent.com`
     - Client Secret: `GOCSPX-xxxxxxxxxxxxx`
   - Copy these values!

### Step 3: Update .env File

```env
GOOGLE_CLIENT_ID=your_client_id_here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Example:**
```env
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-1234567890abcdefghijk
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Step 4: Clear Config Cache

```powershell
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Testing Google OAuth

### Test Flow:
1. Visit: http://localhost:8000/login
2. Click "Login with Google" button
3. Select your Google account
4. Grant permissions
5. Should redirect to dashboard
6. Check database - new user should be created with google_id

### Verify in Database:
```sql
SELECT name, email, google_id, role FROM users WHERE google_id IS NOT NULL;
```

---

## 🎨 Login Page with Google Button

The login page will automatically show the Google OAuth button once implemented.

---

## ⚠️ Common Issues

### Error: "redirect_uri_mismatch"
**Solution:** Make sure the redirect URI in Google Console exactly matches:
```
http://localhost:8000/auth/google/callback
```
Note: No trailing slash, exact port (8000)

### Error: "invalid_client"
**Solution:** 
- Check CLIENT_ID and CLIENT_SECRET in .env
- Run `php artisan config:clear`

### Error: "Access blocked: This app's request is invalid"
**Solution:**
- Make sure OAuth consent screen is configured
- Add your Gmail as a test user

---

## 🔒 Security Notes

**For Development:**
- Current setup is for localhost testing only
- Use "External" with test users

**For Production:**
- Apply for OAuth verification
- Use HTTPS
- Update redirect URI to production domain
- Set APP_ENV=production

---

## 📝 .env Template

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

---

## ✅ Success Checklist

After setup:
- [x] Google Cloud project created
- [x] OAuth credentials created
- [x] .env updated with credentials
- [x] Config cache cleared
- [x] Can see "Login with Google" button
- [x] Can login with Google account
- [x] New user created in database with google_id

---

## 🎯 Next Steps

Once Google OAuth works:
1. Test login with multiple Google accounts
2. Check user roles (should default to 'user')
3. Admins can manually change roles in database if needed
4. Continue to Phase 1: Map Integration
