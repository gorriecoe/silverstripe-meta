# Silverstripe meta
Improves silverstripes html meta data options.

## Installation
Composer is the recommended way of installing SilverStripe modules.
```
composer require gorriecoe/silverstripe-meta
```

## Requirements

- silverstripe/cms ^4.0

## Maintainers

- [Gorrie Coe](https://github.com/gorriecoe)

## Usage
Add the `$MetaTags` in the `head` as you would normally do in [SilverStripe](https://docs.silverstripe.org/en/4/developer_guides/templates/common_variables/#meta-tags).  The only difference is that `$MetaTags` can no longer be passed false to prevent the title tag
```
<head>
    {$MetaTags}
</head>
```

### Modify title tag
If you want to modify title tag, include `$meta_data` variable with `MetaTitle` key in your page.
```php
<?php
class MyPage extends Page
{
    private static $meta_data = [
        'MetaTitle' => [
            'MetaTitle',
            'Title',
            'SiteConfig.MetaTitle'
        ]
    ];
}
```
In the example above MetaTitle will use data from MetaTitle if found, if not it will fallback to Title and finally fall back to the SiteConfig MetaTitle.

Other modifiable tags include: `MetaTitle`, `MetaDescription`, `MetaRobots`, `TwitterTitle`, `TwitterDescription`, `TwitterImage`, `TwitterSite`, `TwitterCreator`, `OGTitle`, `OGImage`, `OGImageType`, `OGUrl`, `OGDescription`, `OGSiteName`, `FBAuthor`, `FBPublisher`, `GplusAuthor`  and `GplusPublisher`
