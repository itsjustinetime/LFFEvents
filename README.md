# LFFEvents
Two plugins for the open source flat-file CMS system Bludit.  One theme (jttheme) for Bludit

The first, LFFEvents allows the creation & management of data for a social events app.
The second, lff-data allows data created by LFFEvents to be displayed in pages using shortcodes

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

# lff-data
Available shortcodes
[youtube]  => example [youtube=VIDEOID]

[lffvideo] => example [lffvideo=/path/to/video/file.mp4]

[lffevents] displays upcoming events in a slider form

[lffeventsgrid] displays upconing events in a responsive grid format

[lffvenues] displays venues set to 'recommended'

[lffservices] displays service providers

[lfffuture] lists upcoming LFF dates based on the next 12 first fridays of each month

[lffhighlights] displays venue highlights fron LFFEvents


# app

The front end for all of the event, highlight, venue & service data set by the LFFEvents plugin.

