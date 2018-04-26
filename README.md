# Silverstripe meta
Improves silverstripes html meta data options.

## Installation
Composer is the recommended way of installing SilverStripe modules.
```
composer require gorriecoe/silverstripe-meta
```

## Requirements

- silverstripe/cms ^4.0
- gorriecoe/silverstripe-sreg ^1.2

## Maintainers

- [Gorrie Coe](https://github.com/gorriecoe)

## Usage
Add the `$MetaTags` in the `head` as you would normally do in (silverStripe)[https://docs.silverstripe.org/en/4/developer_guides/templates/common_variables/#meta-tags].  The only difference is that can no longer be passed false to prevent the title tag
```
<head>
    {$MetaTags}
</head>
```

### Modify title tag
If you want to modify title tag, include `$meta_tags` variable with `MetaTitle` key in your page.
```php
<?php
class MyPage extends Page
{
    private static $meta_tags = [
        'MetaTitle' => '{$MetaTitle|Title|SiteConfig.MetaTitle} &raquo; {$SiteConfig.Title}'
    ];
}
```

Other modifiable tags include: `MetaTitle`, `MetaDescription`, `MetaRobots`, `TwitterTitle`, `TwitterDescription`, `TwitterImage`, `TwitterSite`, `TwitterCreator`, `OGTitle`, `OGImage`, `OGImageType`, `OGUrl`, `OGDescription`, `OGSiteName`, `FBAuthor`, `FBPublisher`, `GplusAuthor`  and `GplusPublisher`
