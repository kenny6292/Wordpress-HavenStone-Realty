# HavenStone Realty

Production-oriented WordPress real-estate website and custom CMS foundation.

## Architecture

- WordPress is the production CMS and website runtime.
- `wp-content/themes/havenstone` contains the custom responsive theme.
- `wp-content/plugins/havenstone-core` contains property, agent, enquiry, viewing-request, search and security functionality.
- GitHub provides version control for custom code.
- WordPress Media Library stores property imagery; uploads and WordPress core are intentionally excluded from Git.

## Core capabilities

- Property custom post type with structured metadata
- Property types, locations and status taxonomies
- Advanced property search and sorting
- Property detail pages with galleries, amenities and similar listings
- Agent profiles and property assignment
- Enquiry, viewing-request and property-request workflows
- Admin lead statuses and lead actions
- Honeypot, nonce, rate-limit and origin validation for public forms
- Optional Resend email delivery with WordPress mail fallback
- SEO, canonical, Open Graph and RealEstateListing schema support
- Responsive layouts and accessibility-focused keyboard navigation
- Security headers and reduced WordPress surface exposure

## WordPress setup

1. Install a supported WordPress release on the production host.
2. Copy the custom theme and plugin into `wp-content`.
3. Activate **HavenStone Realty Core** and the **HavenStone** theme.
4. Save **Settings → Permalinks** once after activation.
5. Create the required pages using the exact slugs:
   - `about-us`
   - `services`
   - `locations`
   - `contact`
   - `request-a-property`
   - `blog`
6. Configure the front page under **Settings → Reading**.
7. Create and assign Primary and Footer navigation menus.
8. Add property types, locations, statuses, properties and agents.
9. Configure the WordPress admin email and production email delivery.
10. Complete the launch checklist in `docs/LAUNCH-CHECKLIST.md`.

## Security and configuration

Do not commit:
- WordPress core
- Uploads
- Database dumps
- API keys
- SMTP/Resend credentials
- Environment-specific secrets
- Production backups

The Resend integration is intentionally configuration-driven. A verified sending domain and real credentials must be supplied in the production environment before relying on Resend delivery.

## Development

Custom code should remain compatible with the project's PHP 8.1+ requirement. Keep presentation in the theme and business/data functionality in the core plugin.

## Launch status

The custom GitHub codebase is prepared for WordPress configuration and final browser/hosting QA. A production launch is not complete until the WordPress database, media, domain, HTTPS, email credentials, menus, page assignments and real property content are configured and tested.
