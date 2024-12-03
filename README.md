# LFFEvents
A social event management plugin for the open source flat-file CMS system Bludit.

# LFFEvents
Once added to Bludit, open the settings page from the sidebar menu.  Set up the various categories there: 
Venue Categories, eg Bars, Hotels, Beer Houses etc

Highlight Categories, eg Venue Offer, Hotel Offer, Promotion, Special Event etc

Image Categories, eg event, venue, service etc

Events are calendar based entries which have their own data assigned.  A venue can be assigned to every event and an image chosen from uploaded images of the category 'event'.

Venues can have data such as name, address, GPS location, image & social media links added.

Services can be configured similarly to venues.

Highlights are calendar based and are intended to be assigned to venues in that a highight displayed in the app will show the image & address data assigned to the associated venue.

Images can be uploaded to various categories by dragging & dropping files onto the 'add image' page.  Uploaded images can be renamed & assigned a different category in each image's 'edit' page.

Passcodes for the user frontend app access can be managed from the 'passcodes' admin page.  Here, a name, value (the desired passcode) and expiry date are set.

All data is output in JSON format in the bl-content/lff-events/json directory

