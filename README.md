# WordPress Starter Block Theme

A WordPress block starter theme with full site editing capabilities and organized asset management.

### Requirements

- WordPress 6.0 or higher
- PHP 5.7 or higher
- Node.js and npm

### Directory Structure

```
├── assets/                # Theme assets
│   ├── build/             # Compiled assets
│   ├── fonts/             # Custom fonts
│   ├── images/            # Theme images
│   └── src/               # Source files
│       ├── blocks/        # Custom block source files
│       ├── js/            # JavaScript files
│       └── scss/          # SASS stylesheets
├── inc/                   # Theme functionality
│   ├── classes/           # PHP classes
│   ├── helpers/           # Helper functions
│   └── traits/            # PHP traits
├── parts/                 # Template parts (header, footer)
├── patterns/              # Block patterns
├── templates/             # Page templates
└── theme.json             # Theme configuration
```

### Asset Structure

The assets/src directory contains:

- `blocks/`: Custom block development files
- `js/`: Theme JavaScript, split into editor and frontend
- `scss/`: Organized stylesheet architecture
  - `globals/`: Variables and base styles
  - `utils/`: SCSS functions and mixins
  - `templates/`: Template-specific styles

### PHP Architecture

The inc directory manages PHP functionality:

- `classes/`: Core theme classes
  - `class-assets.php`: Asset loading
  - `class-blocks.php`: Block registration
  - `class-theme.php`: Theme setup
- `helpers/`: Utility functions and autoloader
- `traits/`: Reusable PHP traits

### Getting Started

- Install dependencies: `npm install`
- Build assets: `npm run build`
- Development mode: `npm run start`

### Creating Custom Blocks

Run the block creation wizard:

`npm run create:block`

The wizard will guide you through:

- Block Type Selection:

  - Dynamic Block
  - Static Block
  - Server Side Rendered Block

- Block Configuration:
  - Title
  - Slug
  - Namespace
  - Description
  - Keywords
  - Icon
  - Text Domain
  - Category

#### Example

```bash
? What type of block would you like to create?
1. Dynamic Block
2. Static Block
3. Server Side Rendered Block
4. Exit

? Enter the block title: (Example Block)
? Enter the block slug: (example-block)
? Enter the block namespace: (starter-block-theme)
```

Generated blocks are placed in `assets/src/blocks/[block-name]`
