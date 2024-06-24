# Syntech Dashboard Package

 
A custom Laravel package for creating dashboard components.

## Installation

You can install the package via Composer:

```bash
composer require syntech/dashboard:*

Usage

This package provides a custom Artisan command to create a new dashboard controller along with the associated resources (DataTable, Request, Resource, and View).
Creating a New Dashboard Component

Run the following Artisan command to create a new dashboard component:

php artisan syntech:create {Namespace\\ControllerName}

For example:

php artisan syntech:create Dashboard\\Blog

This will generate the following files:

    Controller: App\Http\Controllers\Dashboard\BlogController.php
    DataTable: App\DataTables\Dashboard\BlogDataTable.php
    Request: App\Http\Requests\Dashboard\BlogRequest.php
    Resource: App\Http\Resources\Dashboard\BlogResource.php
    View: resources/views/dashboard/blog.blade.php
