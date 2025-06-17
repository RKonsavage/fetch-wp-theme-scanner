#Fetch Theme Malware Scanner

A lightweight WordPress plugin that scans your active theme’s PHP files for suspicious or potentially malicious code patterns. It detects usage of risky PHP functions such as `eval()`, `base64_decode()`, `shell_exec()`, and others commonly found in malware.

---

## Features

- Recursively scans all PHP files in the active WordPress theme directory  
- Flags suspicious PHP functions and risky code patterns  
- Displays a clear report in the WordPress admin dashboard under **Appearance > Malware Scanner**  
- Easy to use with a single click scan button  
- No external dependencies or complex setup

---

## Installation

1. Download or clone this repository  
2. Upload the `simple-theme-malware-scanner` folder to your WordPress `wp-content/plugins/` directory  
3. Activate the plugin through the **Plugins** menu in WordPress  
4. Navigate to **Appearance > Malware Scanner** to start scanning your theme files

---

## Usage

- Click the **Scan Theme Files** button to perform a scan  
- Review the results table for any suspicious functions found in your theme files  
- No automatic removal actions included — scan only, to help identify potential issues

---

## Supported Suspicious Functions

- `eval`  
- `base64_decode`  
- `shell_exec`  
- `exec`  
- `passthru`  
- `system`  
- `preg_replace` (with `/e` modifier)  
- `assert`  
- `create_function`  
- `popen`

---

## Notes

- This plugin provides a basic detection mechanism and is **not a full malware scanner**.  
- Use it as part of your security workflow alongside professional security plugins like Wordfence or Sucuri.  
- Always backup your site before making any changes based on scan results.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Author

Robert Konsavage  
[robkonsavage.space](https://robkonsavage.space)  
[GitHub Profile](https://github.com/your-github-username)

