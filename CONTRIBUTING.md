# Contributing to Originalapp Reports Module
We welcome contributions and are grateful for your interest in improving the Originalapp_Reports Magento 2 module! Whether you're fixing a bug, proposing a feature, or enhancing documentation, your input helps make the module better for everyone.

## 📋 Prerequisites
Before you begin, make sure you have:

- A working Magento 2 development environment.
- Familiarity with PHP, XML, and Magento module structure.
- Installed and enabled this module in developer mode.

## 🛠 How to Contribute
### 1. Fork and Clone
Fork the repository to your GitHub account and clone it locally:

```bash
git clone https://github.com/aelmizeb/magento2-reports.git
```

### 2. Create a Branch
Use a clear and descriptive branch name:

```bash
git checkout -b fix/order-status-translation
```

### 3. Make Your Changes
Keep changes focused and relevant.

Ensure your code follows Magento's Coding Standards.

For features, add configuration options if needed under the appropriate section.

### 4. Test Your Code
Make sure it works with Magento’s backend UI.

Clear cache and recompile after code changes.

```bash
bin/magento cache:flush
bin/magento setup:di:compile
```

### 5. Commit and Push

```bash
git add .
git commit -m "Fix: Translated order statuses for monthly sales"
git push origin fix/order-status-translation
```

### 6. Open a Pull Request
Open a PR on the main or develop branch of the original repository. Include:

- A clear title and description.
- Reference any related issue (e.g., Closes #42).
- A summary of your changes.

## 💡 Tips
- Use English for code comments and commits.
- Avoid mixing unrelated changes in one pull request.
- Be respectful in communication and receptive to feedback.

## 🧪 Testing Guidelines
If you're submitting bug fixes or new features, include:

- Steps to reproduce the issue.
- Screenshots or logs if applicable.
- Manual testing details or unit/integration tests.

## 🤝 Code of Conduct
By contributing, you agree to follow our [Code of Conduct](https://github.com/aelmizeb/magento2-reports/blob/main/CODE_OF_CONDUCT.md).
