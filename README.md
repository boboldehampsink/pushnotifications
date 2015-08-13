Push Notifications (iOS/Android) plugin for Craft CMS
=================

Plugin that allows you to register devices and send push notifications to them.

Features:
- Able to register platforms and device tokens per app and platform
- Keeps a history of sent messages
- Predefine commands to send with the notification payload
- If you install the [Cronjob Manager Plugin](http://github.com/boboldehampsink/cronjob) you can schedule notifications

Todo:
- Add WP8 support
	- Finish ZendService Mpns/Wns service and submit to Zend(?)
	- Finish NotificationPusher fork which implements Mpns/Wns adapter and submit Pull Request

Important:
The plugin's folder should be named "pushnotifications"

Changelog
=================
###0.2.1###
- Use scheduled date in element index
- Fixed schedule date not showing/saving
- Added schedule as criteria attribute

###0.2.0###
- If you install the [Cronjob Manager Plugin](http://github.com/boboldehampsink/cronjob) you can now schedule notifications in the future
- Fixed a bug where deleting a notification was broken

###0.1.2###
- Get max power when sending notifications
- Show command in element index as label instead of value
- Added Dutch translations

###0.1.1###
- Added the ability to unregister a device

###0.1.0###
- First beta, major refactoring
- Platforms are now per app
- Better editing capabilities
- More uniform way of sending a custom payload
- PushNotifications_PushService::sendNotification now returns a list with addressed devices

__Warning: you cannot upgrade from 0.0.3, do a fresh install__

###0.0.3###
- Added the ability to register a device anonymously. This could function as the endpoint for an app.
- Added CSRF input support
- Added the ability to use titles in ios push notifications

###0.0.2###
- Replaced param with pre-defined commands in app settings

###0.0.1###
- Initial push to GitHub
