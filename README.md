# Advanced Reports for Magento 2 FREE version

![WIP](https://img.shields.io/badge/status-WIP-orange?style=flat-square)

> ⚠️ **WORK IN PROGRESS**  
> This module is currently under active development. It is not ready for production use. Please do not install it on a live Magento 2 store.


## 🔎 Preview
![Module Preview](./view/adminhtml/web/images/preview.png)

## ✨ Description

Advanced Reports for Magento 2 is a free extension which helps stores quickly access advanced reports on the Dashboard. As your shop grows, so does the amount of data you have to deal with every day. Eventually, it reaches a point where you find yourself in dire need of a tool that can handle analytics and insights for you. This module provides visual dashboards and detailed statistics to help you make informed business decisions.

## ❗ Disclaimer

This module has the potential to make irreversible changes to your database if set up incorrectly. Use at your own risk. Always test in a staging environment before deploying to production.

## 🛠 Features

- Sales overview dashboard
- Sales by country/location chart
- Customer growth over time
- Annual sales objective tracking
- Top-selling products chart (using ECharts)
- Dynamic, filterable charts for clear data visualization
- Fully integrated with the Magento 2 admin panel
- Responsive design and clean UI

## 💻 Installation

Run the following commands:

```bash
composer require aelmizeb/magento2-reports
php bin/magento module:enable Originalapp_Reports
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

## 🧩 Compatibility
Magento Open Source 2.4.x+

PHP 7.4 / 8.1+

Compatible with most custom and third-party themes

## 🚀 Support
This is a free and open-source module provided as-is with no guaranteed support. However, contributions, suggestions, or issues can be submitted via GitHub.


## 🤝 Contributing
Contributions are welcome!
If you'd like to contribute:

- Fork the repository
- Create your feature branch (git checkout -b feature/my-feature)
- Commit your changes (git commit -m 'Add new feature')
- Push to the branch (git push origin feature/my-feature)
- Open a Pull Request

<!--## 👨‍💻 Contributors

[![Contributors](https://img.shields.io/github/contributors/aelmizeb/nuxt-dashboard?style=for-the-badge)](https://github.com/aelmizeb/nuxt-dashboard/graphs/contributors)-->


## 🛡 License
This project is licensed under the MIT License.
