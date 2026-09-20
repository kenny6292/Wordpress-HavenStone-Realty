# HavenStone Realty — WordPress Launch Checklist

## 1. Install the custom code
- Upload/clone the repository into the WordPress installation.
- Copy `wp-content/themes/havenstone` into the site's themes directory.
- Copy `wp-content/plugins/havenstone-core` into the plugins directory.
- Activate **HavenStone Realty Core**.
- Activate the **HavenStone** theme.
- Visit **Settings → Permalinks** and save once after activation.

## 2. Create the WordPress pages
Create published Pages using these exact slugs so the matching custom templates are selected:
- `about-us`
- `services`
- `locations`
- `contact`
- `request-a-property`
- `blog`

Set the site's front page under **Settings → Reading**.
Create a navigation menu under **Appearance → Menus** and assign it to the Primary and Footer locations.

## 3. Configure real-estate content
Create:
- Property Types
- Property Locations
- Property Status terms
- Properties
- Agents

For every property, verify:
- Featured image
- Price and price label
- Type/location/status
- Bedrooms/bathrooms/parking
- Area/address
- Gallery
- Amenities
- Optional map URL
- Assigned agent
- Featured flag where appropriate

For every agent, verify:
- Profile image
- Phone
- WhatsApp
- Email
- Role
- License/registration
- Biography

## 4. Forms and email
Before launch:
- Confirm the WordPress admin email is correct.
- Submit an enquiry from a property page.
- Submit a viewing request.
- Submit a property request.
- Confirm each submission appears in WordPress admin.
- Configure Resend only after a sending domain is verified and the API credentials are stored as server environment/configuration values.
- Never commit API keys or other secrets to GitHub.
- Verify reply-to behavior and deliverability after Resend is configured.

## 5. SEO and indexing
- Set the final site title and tagline.
- Confirm canonical URLs.
- Confirm Open Graph metadata and social image.
- Confirm property pages emit RealEstateListing structured data.
- Generate/verify the XML sitemap through the site's SEO stack or WordPress configuration.
- Submit the production sitemap to Google Search Console and Bing Webmaster Tools.
- Confirm staging/development environments are not indexable.

## 6. Security
- Use HTTPS.
- Keep WordPress core, themes and plugins updated.
- Use strong administrator credentials and least-privilege accounts.
- Keep XML-RPC disabled unless a documented integration requires it.
- Keep backups enabled.
- Store secrets outside the repository.
- Review spam/rate-limit behavior on public forms.
- Test nonces and permission checks after deployment.

## 7. Performance and accessibility QA
Test at minimum:
- Mobile width around 320–390px
- Tablet width around 768px
- Desktop width around 1280px+
- Keyboard-only navigation
- Visible focus states
- Skip-to-content link
- Reduced-motion preference
- Images with meaningful alt text where appropriate
- No broken navigation links
- No console errors
- Fast-loading optimized images
- Browser cache/CDN configuration where available

## 8. Functional launch tests
- Home page loads.
- Property archive loads.
- Search filters work independently and together.
- Price sorting works.
- Pagination preserves filters.
- Individual property URLs load.
- Gallery links open correctly.
- Agent profiles load.
- Call/WhatsApp/email CTAs use the intended values.
- Map links open correctly when configured.
- Enquiry form submits.
- Viewing form submits.
- Property request form submits.
- Blog archive and individual posts load.
- 404 page behaves correctly.
- Admin property fields save and reload correctly.
- Media-library gallery selection works.

## 9. Production content
Replace all placeholder/demo content before launch:
- Property listings
- Property photographs
- Agent profiles
- Contact information
- Company address
- Business hours
- Social links
- Legal/privacy information
- Testimonials
- Blog content

## 10. Final launch gate
The repository contains the production custom theme/plugin code, but a real WordPress launch still requires the WordPress admin configuration, database content, media, domain/hosting, HTTPS, email credentials and final browser testing listed above.
