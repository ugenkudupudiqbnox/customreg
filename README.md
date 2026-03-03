# Dr. BRAOU Custom Registration Form for Moodle (local_customreg)

A highly robust Moodle plugin designed to enforce identity verification for new users and manage manual course enrollment approvals.

## 🚀 Key Features

- **Identity Verification Enforcement**: Automatically redirects new students to an ID upload page after account creation.
- **Searchable Course Settings**: Configure up to **5 featured courses** using a modern, searchable autocomplete interface for selection during signup.
- **Decoupled Approval Workflow**: 
  - **Identity Verification**: Approve or deny government-issued IDs.
  - **Granular Enrollment**: Approve or deny individual course requests per user.
- **Automated Notifications**: Sends custom emails to users when registration or course enrollment status changes.
- **Admin Communication**: Integrated comment fields in the approval modals with pre-populated, editable default text for faster processing.
- **Seamless Document Preview**: View uploaded ID images directly within Moodle using Bootstrap modals (no new tabs required).
- **25+ Languages Supported**: Comprehensive localization for global and regional audiences (including 11+ Indian languages).
- **Modern Hook Architecture**: Built using the latest Moodle (4.x/5.x) namespaced hooks for maximum compatibility and performance.

---

## 🛠 Installation

Choose one of the following options to install the plugin.

### Option A: Manual Installation (GitHub Checkout)
Recommended for developers or servers with shell access.

1. Navigate to your Moodle local plugins directory:
   ```bash
   cd /var/www/moodle/local/
   ```
2. Clone the repository:
   ```bash
   git clone https://github.com/ugenkudupudiqbnox/customreg.git customreg
   ```
3. Ensure proper permissions:
   ```bash
   sudo chown -R www-data:www-data customreg
   ```
4. Log in to Moodle as a Site Administrator and go to **Site Administration > Notifications** to complete the database upgrade.

### Option B: Web Installation (ZIP File)
Recommended for most users.

1. Download the latest version ZIP file from the [GitHub Releases](https://github.com/your-repo/customreg/releases) page.
2. Log in to Moodle as a Site Administrator.
3. Navigate to **Site Administration > Plugins > Install plugins**.
4. Drag and drop the downloaded ZIP file into the **ZIP package** area.
5. Click **Install plugin from the ZIP file** and follow the on-screen validation and upgrade prompts.

---

## ⚙️ Configuration

Navigate to **Site Administration > Plugins > Local Plugins > Custom Registration**.

- **Select Featured Courses**: Use the 5 distinct searchable dropdowns to pick the primary courses students should see during the identity upload process.
- **Auto-Enrolment**: Users are only enrolled in their chosen courses *after* an administrator manually approves the specific course request.

---

## 👮 Admin Dashboard

Accessed via **Site Administration > Plugins > Local Plugins > Custom Registration > Manage**.

- **Approve User**: Validates the student's legal identity and removes any login blocks.
- **Approve Course**: Enrolls the user in the selected course and sends a notification (if configured).
- **Deny Action**: Allows the admin to reject an ID, prompting the user to re-upload a clearer image.

---

## 🌍 Supported Languages

The plugin is localized for a global audience:

- **International**: Arabic, Chinese (Simplified), Dutch, English, French, German, Indonesian, Italian, Japanese, Korean, Portuguese, Russian, Spanish, Turkish.
- **Indian Regional**: Bengali, Gujarati, Hindi, Kannada, Malayalam, Marathi, Odia, Punjabi, Tamil, Telugu, Urdu.

---

## 📝 Developer Reference

- **Plugin Type**: Local (`local_`)
- **DB Table**: `mdl_local_customreg`
- **Minimum PHP**: 8.1
- **Minimum Moodle**: 4.0

### Database Schema Notes
- `userid`: Links to Moodle's core user table.
- `status`: Tracks overall verification (`pending`, `approved`, `denied`).
- `courseidsjson`: Stores requested courses until enrollment is processed.

---

## 📄 License

Distributed under the GNU General Public License. See `LICENSE` for more information.
