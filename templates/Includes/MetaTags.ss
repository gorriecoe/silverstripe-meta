<title>{$MetaTitle} &raquo; {$SiteConfig.Title}</title>
<% if MetaCharset %>
    <meta charset='{$MetaCharset}'>
    <meta http-equiv='Content-type' content='text/html; charset={$MetaCharset}' />
<% end_if %>
    <meta generator="SilverStripe" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<% if MetaDescription %>
    <meta description="{$MetaDescription.Summary(160)}" />
<% end_if %>
<% if MetaRobots %>
    <meta robots="{$MetaRobots}" />
<% end_if %>
<% if TwitterTitle %>
    <meta twitter:title="{$TwitterTitle}" />
<% end_if %>
<% if TwitterDescription %>
    <meta twitter:description="{$TwitterDescription.Summary(160)}" />
<% end_if %>
<% if TwitterImage %>
    <meta twitter:card="summary_large_image" />
    <meta twitter:image="{$TwitterImage}" />
<% end_if %>
<% if TwitterSite %>
    <meta twitter:site="@{$TwitterSite}" />
<% end_if %>
<% if TwitterCreator %>
    <meta twitter:creator="@TwitterCreator" />
<% end_if %>
<% if OGTitle %>
    <meta property="og:title" content="{$OGTitle}" />
<% end_if %>
    <meta property="og:type" content="website" />
<% if OGImage %>
    <meta property="og:image" content="{$OGImage}" />
    <meta property="og:image:type" content="{$OGImage.MimeType}" />
<% end_if %>
<% if OGUrl %>
    <meta property="og:url" content="{$OGUrl}" />
<% end_if %>
<% if OGDescription %>
    <meta property="og:description" content="{$OGDescription.Summary(160)}" />
<% end_if %>
<% if OGSiteName %>
    <meta property="og:site_name" content="{$OGSiteName}" />
<% end_if %>
<% if FBAuthor %>
    <meta property="article:author" href="{$FBAuthor}" />
<% end_if %>
<% if FBPublisher %>
    <meta property="article:publisher" href="{$FBPublisher}" />
<% end_if %>
<% if GplusAuthor %>
    <link rel="author" href="{$GplusAuthor}" />
<% end_if %>
<% if GplusPublisher %>
    <link rel="publisher" href="{$GplusAuthor}" />
<% end_if %>
