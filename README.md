## Contact Form KMD Plugin for WordPress
* The "Contact Form KMD" is a custom WordPress plugin tailored to handle form submissions for various contact forms on your website.


![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/c9354a52-0a95-4c2e-8f0d-434a2e4709e3)
![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/cc47ada3-8dc7-4c72-b5de-7a9959f7dc45)


## Features
* Form Management: Create and manage multiple forms for various needs.
* Form Submissions: Collect and store form submissions safely in your WordPress database.
* Admin Dashboard: View all submissions through the admin dashboard, filter by form, and manage individual entries.
* Secure Handling: Uses WordPress built-in functions for data validation and sanitization.
## Installation
* Download the "Contact Form KMD" plugin.
* Upload the plugin to your /wp-content/plugins/ directory.
## Activate the "Contact Form KMD" plugin through the 'Plugins' menu in WordPress.
![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/9714562c-a269-4dae-bf17-0470fa001d86)

## Navigate to the "Contact Forms KMD" menu item in your admin sidebar to manage forms and view submissions.
![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/708ca0bb-11a2-42ff-b949-039bc58a8e16)

## Usage
*Creating a Form:
* Go to the "Manage Forms" tab.
* Provide a unique name for your form and click the "Create" button.
* Use the generated shortcode on any post or page to display the form to your users.
  
  ![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/bb18bfd6-9efe-4258-930f-9d98e7c03171)
  ![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/cda3ab67-f14d-404b-bfb8-483cc8102628)
  ![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/4cd1fb21-724b-454c-b28c-315cdcda576c)

## Seed Function in Contact Form KMD Plugin
The seed function in the "Contact Form KMD" WordPress plugin is responsible for populating the plugin's database tables with initial or sample data. This is particularly useful during the development phase, testing, or even when setting up the plugin for the first time on a new installation, as it gives users and developers a clear idea of how the data structures work and provides some data to work with right off the bat.

* How It Works:
Initialization:
The seed function, when invoked, calls two other functions: seed_forms and seed_submissions.

## Populating Forms:

* The seed_forms function populates the kmd_forms table with some sample forms.
Before inserting a new form, it checks to ensure that a form with the same name doesn't already exist, thus preventing duplicate entries.
Populating Submissions:
![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/1b2ae793-d388-4ce8-8350-e6ecbff17c04)

* The seed_submissions function inserts sample submissions into the kmd_submissions table.
It links each submission to a form by using the form's ID.
The function ensures no duplicate entries are added based on a unique attribute like email.
![image](https://github.com/jefersondepaula/contact-form-kmd/assets/55894519/7a9371d5-f0bc-4ff2-a2b6-3dc785ea9754)

## Viewing Submissions:

* Go to the "View Submissions" tab to see a list of all form submissions.
You can view the details of each submission.
Use the "Delete" button to remove any unwanted submissions.
* Customization
The plugin offers hooks and filters for developers to extend its functionality and appearance. Refer to the codebase for available actions and filter hooks.

* Security
Always escape output data to prevent XSS attacks.
Use WordPress nonce for form submissions to ensure the request is valid and coming from your site.
