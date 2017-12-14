# Google Tag Manager - Extension for Magento 1.x

This extension allows you to integrate Google Tag Manager on your Magento site so that you can track
and gather valuable visitor data from your website and make important decisions to grow your
business. This extension also provides e-commerce transaction tracking feature. You just need to enter
your Tag Manager container id in the extension's configuration settings.

[Google Tag Manager](https://support.google.com/tagmanager/answer/6102821?hl=en) allows you to quickly and easily update tags and code snippets on your website or
mobile app, such as those intended for traffic analysis and marketing optimization. You can add and
update AdWords, Google Analytics, Floodlight, and 3rd party or custom tags from the Google Tag
Manager user interface instead of editing site code. This reduces errors, frees you from having to
involve a web developer, and allows you to quickly deploy new features or content onto your site.

Google Tag Manager are FREE of charge services offered by Google. You need to create a separate
Google Tag Manager account from [here](https://tagmanager.google.com/?hl=en#/admin/accounts/create) and link the account with your Magento store by adding the
"Container ID" of your Google Tag Manager account to the current extension's configuration setting.

## Features

- Tracks views of the product
- Tracks categories and price of the product
- Tracks transaction of the product by SKU, name, category, price and quantity
- Tracks the transaction purchase revenue, tax and shipping cost
- Tracks the coupon code

## Installation

1. The module's files should be placed in Magento root folder.
2. Login to your Magento site's admin
3. Disable Compilation if it is enabled: `System -> Tools -> Compilation`
4. Refresh Cache: `System -> Cache Management`
5. That's all. The extension should be installed by now.
6. You can re-enable Compilation if it was enabled before.

## Configuration Settings

1. Login to your Magento site's admin
2. Go to `System → Configuration` page
3. On left sidebar, click on `CHAPAGAIN EXTENSIONS → Google Tag Manager` menu
4. Here, you will see the following settings from where you can: 
    - Enable/Disable module
    - Enable/Disable data layer 
    - Add container ID of your site from Google Tag Manager
