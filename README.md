---

# ShellTon2 - Advanced PHP File Manager

## Description

**ShellTon2** is an advanced and secure web-based file manager built with PHP, designed for seamless management of files and directories on your server. This project extends basic file management features with capabilities such as multi-file upload, directory creation, user authentication, and detailed logging of user actions. It provides a complete interface for managing files through a web browser, while keeping security and user-friendly navigation at the forefront.

## Key Features

- **Directory Navigation**: View and navigate through your server's directories.
- **File Management**: Delete, rename, edit, and download files directly from the web interface.
- **Folder Management**: Create and delete directories with ease.
- **Multi-File Upload**: Upload multiple files at once with the option to select files from your local system.
- **File Permissions Management**: (In development) Extendable feature for setting user-specific permissions.
- **Action Logging**: Every action (file creation, modification, deletion) is logged for auditing purposes.
- **User Authentication**: A simple login system to authenticate users and restrict access.
- **Secure Operations**: Validates file paths and user inputs to prevent security vulnerabilities like directory traversal and XSS attacks.

## Installation

### Prerequisites

- A web server running PHP 7.4 or higher.
- The project should be placed in a directory accessible by your web server.

### Installation Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/HackfutSec/ShellTon2.git
   cd ShellTon2
   ```

2. **Set up your server**:
   - Place the files in your web server's root directory (e.g., `public_html` for Apache).
   - Ensure your web server is configured to execute PHP files.

3. **Access the file manager**:
   - Open your browser and navigate to `http://localhost/ShellTon2` (or the appropriate URL for your server).

4. **Login**:
   - To access the file manager, you'll need to authenticate. If you don't have credentials, you'll need to implement your own user authentication system (this version uses a simple session-based system).

## Code Structure

### 1. **Authentication and Session Management**
   - The file manager uses session management for user authentication. On each request, it checks whether the user is logged in (`$_SESSION['authenticated']`).

### 2. **Directory and File Management**
   - **listDirectory()**: Scans and displays the contents of the current directory, listing files and subdirectories separately.
   - **File Operations**: 
     - **Delete**: Files and directories can be deleted.
     - **Rename**: Files can be renamed directly in the interface.
     - **Edit**: Allows editing of text files, with changes saved to the server.
     - **Download**: Files can be downloaded directly from the web interface.
     - **Create Directory**: Create new directories in the current folder.
     - **Upload Files**: Users can upload multiple files at once.

### 3. **Action Logging**
   - Each user action (e.g., file creation, deletion, editing) is logged into an `action_log.txt` file for audit purposes.

### 4. **Security Features**
   - **Directory Traversal Protection**: The application prevents directory traversal attacks by ensuring the requested directory is within the designated root directory.
   - **Input Validation**: All user inputs are sanitized using PHP's `htmlspecialchars()` to prevent XSS attacks and other malicious input.
   - **Authentication Check**: Ensures only authenticated users can interact with the file manager.

## How It Works

### 1. **User Authentication**
   - The system uses PHP sessions for managing user authentication. A simple `login.php` page (which is not included) can be used to authenticate users. Once logged in, users can access the file manager interface.

### 2. **File and Folder Management**
   - The **listDirectory()** function generates an HTML table of files and directories.
   - Directories are listed at the top, and files are displayed below. Each entry provides links for editing, deleting, renaming, and downloading.
   - Files can be created, renamed, deleted, and uploaded.

### 3. **File Editing**
   - When editing a file, the content is read and displayed in a form where users can modify it. After editing, the changes are saved back to the file.

### 4. **Logging and History**
   - Every action performed (file operations, directory creation, etc.) is logged with the timestamp and username (if authenticated), which helps with tracking and auditing.

## Technologies Used

- **PHP** (7.4 or higher)
- **HTML** for structure
- **CSS** for styling and responsive layout
- **JavaScript** (optional for additional future features)
- **Session Management** for user authentication
- **File Handling**: PHP file operations for reading, writing, renaming, deleting, and uploading files

## Security Considerations

- **Directory Traversal Prevention**: Ensures users cannot navigate outside the allowed root directory.
- **XSS Prevention**: All user inputs (e.g., file names, content) are sanitized using `htmlspecialchars()` to prevent Cross-Site Scripting (XSS) attacks.
- **File Permissions**: Although not fully implemented, the file manager supports the idea of implementing file-specific permissions for read, write, and execute access.

## How to Contribute

1. **Fork the repository**: Click on the "Fork" button on the top-right of this repository.
2. **Make your changes**: Work on your changes locally, fix bugs, or implement new features.
3. **Submit a pull request**: Once you're done, submit a pull request with a detailed description of your changes.

## License

This project is licensed under the [MIT License](LICENSE).

## Screenshots

Here’s an example of the interface:

- **File Manager Interface**: Displays files, folders, and actions such as rename, edit, delete, and download.
- **File Upload Form**: Lets users select multiple files for upload.
- **Directory and File Size Display**: Shows file sizes in human-readable formats (e.g., MB, GB).

---

### Breakdown of Updates:

- **User Authentication**: Simple session-based login system added for restricting access to authorized users only.
- **Multi-file Upload**: Users can now upload multiple files simultaneously via a form.
- **Action Logging**: All actions performed on the file manager are logged into a text file (`action_log.txt`).
- **Improved File and Directory Management**: Added the ability to create directories, improved file management actions like delete, edit, and rename.
- **File Permissions**: While permissions are not fully implemented in this version, placeholders are included for future expansion.

---
