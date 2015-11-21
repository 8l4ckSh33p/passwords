
// ADMIN SETTINGS
// backup_allowed
//		Allow unencrypted backups to be downloaded by users
// days_orange
//		Days from which creation date (and password) gets orange color
// days_red
//		Days from which creation date (and password) gets red color
// disable_contextmenu
//		Disable context menu on whole app
// https_check
//		Check for secure connection before activating app
// icons_allowed
//		Allow users to view website icons, by sending IP to another server
// icons_service
//		Service used for website icons: Google (ggl), DuckDuckGo (ddg)

// USER SETTINGS
// hide_attributes
//		Hide the attributes strength and last changed date
// hide_passwords
//		Hide passwords by showing them as '*****'
// hide_usernames
//		Hide usernames by showing them as '*****'
// icons_show
//		Show website icons, using service selected by admin
// timer
//		Use countdown timer, user will be logged off when it reaches 0

$(document).ready(function() {

	var Settings = function(baseUrl) {
		this._baseUrl = baseUrl;
		this._settings = [];
	};

	Settings.prototype = {
		load: function() {
			var deferred = $.Deferred();
			var self = this;
			$.ajax({
				url: this._baseUrl,
				method: 'GET',
				async: false
			}).done(function( settings ) {
				self._settings = settings;
			}).fail(function() {
				deferred.reject();
			});
			return deferred.promise();
		},

		setUserKey: function(key, value) {
			var deferred = $.Deferred();
			$.ajax({
				url: this._baseUrl + '/' + key + '/' + value,
				method: 'POST'
			}).done(function( data ) {
				deferred.resolve(data);
			}).fail(function() {
				$('.msg-passwords').addClass("msg_error");
				$('.msg-passwords').text(t('passwords', 'Error while saving field') + ' ' + key + '!');
				deferred.reject();
			});
		},

		setAdminKey: function(key, value) {
			var deferred = $.Deferred();
			$.ajax({
				// route is based on URL!
				// /admin1/ only saves a userkey with value 'admin1'
				// so /admin1/admin2 is needed. Ugly but functional.
				url: this._baseUrl + '/' + key + '/' + value + '/admin1/admin2',
				method: 'POST'
			}).done(function( data ) {
				deferred.resolve(data);
			}).fail(function() {
				$('.msg-passwords').addClass("msg_error");
				$('.msg-passwords').text(t('passwords', 'Error while saving field') + ' ' + key + '!');
				deferred.reject();
			});
		},

		getKey: function(key) {
			for (var k in this._settings)
			{
				if (k == key)
					return this._settings[k];
			}
		},
		getAll: function() {
			return this._settings;
		}
	};

	var settings = new Settings(OC.generateUrl('/apps/passwords/settings'));
	settings.load();

// ADMIN SETTINGS

	// fill the boxes
	$('#https_check').prop('checked', (settings.getKey('https_check').toLowerCase() == 'true'));
	$('#backup_allowed').prop('checked', (settings.getKey('backup_allowed').toLowerCase() == 'true'));
	$('#disable_contextmenu').prop('checked', (settings.getKey('disable_contextmenu').toLowerCase() == 'true'));
	
	$('#icons_allowed').prop('checked', (settings.getKey('icons_allowed').toLowerCase() == 'true'));
	if (settings.getKey('icons_service') == 'ddg') {
		$('#ddg_value').prop('checked', true); 
	}
	if (settings.getKey('icons_service') == 'ggl') {
		$('#ggl_value').prop('checked', true); 
	}
	updateIconService();

	$('#days_orange').val(settings.getKey('days_orange'));
	$('#days_red').val(settings.getKey('days_red'));
	updateOrange();
	updateRed();

	// Admin settings
	$('#https_check').change(function () {
		settings.setAdminKey('https_check', $(this).is(":checked"));
	});

	$('#backup_allowed').change(function () {
		settings.setAdminKey('backup_allowed', $(this).is(":checked"));
	});

	$('#disable_contextmenu').change(function () {
		settings.setAdminKey('disable_contextmenu', $(this).is(":checked"));
	});

	$('#icons_allowed').change(function () {
		settings.setAdminKey('icons_allowed', $(this).is(":checked"));
		updateIconService();
	});

	$('#ddg_value').change(function () {
		settings.setAdminKey('icons_service', 'ddg');
	});

	$('#ggl_value').change(function () {
		settings.setAdminKey('icons_service', 'ggl');
	});

	$('#days_red').keyup(function() {
		var val = Number($('#days_red').val());
		if ((val > 0) && (val < 10000) && (val > Number($('#days_orange').val()))) {
			settings.setAdminKey('days_red', val);
			updateRed();
		}
	});

	$('#days_orange').keyup(function() {
		var val = Number($('#days_orange').val());
		if ((val > 0) && (val < 10000) && (val < Number($('#days_red').val())) && (Number($('#days_red').val()) > 0)) {
			settings.setAdminKey('days_orange', val);
			updateOrange();
		}
	});


// PERSONAL SETTINGS
	
	// fill the boxes
	if (settings.getKey('icons_allowed').toLowerCase() == 'true') {
		$('#icons_show').prop('checked', (settings.getKey('icons_show').toLowerCase() == 'true'));
	} else {
		$('#icons_show_div').remove();
	}

	$('#hide_usernames').prop('checked', (settings.getKey('hide_usernames').toLowerCase() == 'true'));
	$('#hide_passwords').prop('checked', (settings.getKey('hide_passwords').toLowerCase() == 'true'));
	$('#hide_attributes').prop('checked', (settings.getKey('hide_attributes').toLowerCase() == 'true'));
	$('#timer').val(settings.getKey('timer'));

	if ($('#timer').val() == 0) {
		$('#timer_bool').prop('checked', false);
		$('#timersettext').hide();
		$('#timer').hide();
	} else {
		$('#timer_bool').prop('checked', true);
		$('#timersettext').show();
		$('#timer').show();
		if ($('#timer').val() < 61) {
			$('#timersettext').text(t('passwords', 'seconds'));
		} else {
			$('#timersettext').text(t('passwords', 'seconds') + ' (' + int2time($('#timer').val()) + ' ' + t('passwords', 'minutes') + ')');
		}
	}


	// Personal settings
	$('#icons_show').change(function () {
		settings.setUserKey('icons_show', $(this).is(":checked"));
	});

	$('#hide_usernames').change(function () {
		settings.setUserKey('hide_usernames', $(this).is(":checked"));
	});

	$('#hide_passwords').change(function () {
		settings.setUserKey('hide_passwords', $(this).is(":checked"));
	});

	$('#hide_attributes').change(function () {
		settings.setUserKey('hide_attributes', $(this).is(":checked"));
	});

	$('#hide_attributes').change(function () {
		settings.setUserKey('hide_attributes', $(this).is(":checked"));
	});

	$('#timer_bool').change(function () {
		if ($('#timer_bool').prop('checked')) {
			settings.setUserKey('timer', 60);
			$('#timersettext').show();
			$('#timer').show();
			$('#timer').val(60);
		} else {
			settings.setUserKey('timer', 0);
			$('#timersettext').hide();
			$('#timer').hide();
		}
	});
	$('#timer').keyup(function () {
		if (!isNumeric($('#timer').val())) {
			OCdialogs.alert(t('passwords', 'Fill in a number between %s and %s').replace('%s', '10').replace('%s', '3599'), t('passwords', 'Use inactivity countdown'), null, true);
			$('#timer').val(60);
			settings.setUserKey('timer', 60);
			return false;
		}
		if ($('#timer').val() > 3599) {
			$('#timer').val(3599);
		}
		if ($('#timer').val() < 10) {
			$('#timer').val(10);
		}
		if ($('#timer').val() < 61) {
			$('#timersettext').text(t('passwords', 'seconds'));
		} else {
			$('#timersettext').text(t('passwords', 'seconds') + ' (' + int2time($('#timer').val()) + ' ' + t('passwords', 'minutes') + ')');
		}
		settings.setUserKey('timer', $('#timer').val());
	});

});

function updateRed() {
	$('#daysRed').text(
		t('passwords', 'Red') 
		+ ': ' 
		+ t('passwords', 'after') 
		+ ' ' 
		+ (Number($('#days_red').val()) + 1) 
		+ ' ' 
		+ t('passwords', 'days')
	);
}
function updateOrange() {
	$('#daysOrange').text(
		t('passwords', 'Orange') 
		+ ': ' 
		+ (Number($('#days_orange').val()) + 1) 
		+ ' ' 
		+ t('passwords', 'to')
	);
}
function updateIconService() {
	if ($('#icons_allowed').prop('checked')) {
		$('#ddg_value').prop("checked", true);
		$('#ggl_value').prop("enabled", true);
		$('#ddg_value').prop("enabled", true);
		$('#ggl_value').prop("disabled", false);
		$('#ddg_value').prop("disabled", false);
	} else {
		$('#ggl_value').prop("checked", false);
		$('#ddg_value').prop("checked", false);
		$('#ggl_value').prop("enabled", false);
		$('#ddg_value').prop("enabled", false);
		$('#ggl_value').prop("disabled", true);
		$('#ddg_value').prop("disabled", true);
	}
}
function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
function int2time(integer) {
	if (integer !== undefined) {
		return new Date(null, null, null, null, null, integer).toTimeString().match(/\d{2}:\d{2}:\d{2}/)[0].substr(3, 5);
	}
}
