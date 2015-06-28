(function(OC, window, $, undefined) {
	'use strict';

	$(document).ready(function() {

		// this passwords object holds all our passwords
		var Passwords = function(baseUrl) {
			this._baseUrl = baseUrl;
			this._passwords = [];
			this._activePassword = undefined;
		};

		Passwords.prototype = {
			load: function(id) {
				var self = this;
				this._passwords.forEach(function(password) {
					if (password.id === id) {
						password.active = true;
						self._activePassword = password;
					} else {
						password.active = false;
					}
				});
			},
			getActive: function() {
				return this._activePassword;
			},
			removeActive: function() {
				var index;
				var deferred = $.Deferred();
				var id = this._activePassword.id;
				this._passwords.forEach(function(password, counter) {
					if (password.id === id) {
						index = counter;
					}
				});

				if (index !== undefined) {
					// delete cached active password if necessary
					if (this._activePassword === this._passwords[index]) {
						delete this._activePassword;
					}

					this._passwords.splice(index, 1);

					$.ajax({
						url: this._baseUrl + '/' + id,
						method: 'DELETE'
					}).done(function() {
						deferred.resolve();
					}).fail(function() {
						deferred.reject();
					});
				} else {
					deferred.reject();
				}
				return deferred.promise();
			},
			removeByID: function(id) {
				var index = id;
				var deferred = $.Deferred();
				//var id = this._activePassword.id;
				// this._passwords.forEach(function(password, counter) {
				// 	if (password.id === id) {
				// 		index = counter;
				// 	}
				// });

				if (index !== undefined) {
					// delete cached active password if necessary
					if (this._activePassword === this._passwords[index]) {
						delete this._activePassword;
					}

					this._passwords.splice(index, 1);

					$.ajax({
						url: this._baseUrl + '/' + id,
						method: 'DELETE'
					}).done(function() {
						deferred.resolve();
					}).fail(function() {
						deferred.reject();
					});
				} else {
					deferred.reject();
				}
				return deferred.promise();
			},
			create: function(password) {
				var deferred = $.Deferred();
				var self = this;

				$.ajax({
					url: this._baseUrl,
					method: 'POST',
					contentType: 'application/json',
					data: JSON.stringify(password)
				}).done(function(password) {
					//self._passwords.push(password);
					//self._activePassword = password;
					//self.load(password.id);
					deferred.resolve();
				}).fail(function() {
					deferred.reject();
				});
				return deferred.promise();
			},
			getAll: function() {
				return this._passwords;
			},
			loadAll: function() {
				var deferred = $.Deferred();
				var self = this;
				$.get(this._baseUrl).done(function(passwords) {
					self._activePassword = undefined;
					self._passwords = passwords;
					deferred.resolve();
				}).fail(function() {
					deferred.reject();
				});
				return deferred.promise();
			},
			updateActive: function(loginname, website, pass) {
				var password = this.getActive();
				password.loginname = loginname;
				password.website = website;
				password.pass = pass;

				return $.ajax({
					url: this._baseUrl + '/' + password.id,
					method: 'PUT',
					contentType: 'application/json',
					data: JSON.stringify(password)
				});
			}
		};

		// this will be the view that is used to update the html
		var View = function(passwords) {
			this._passwords = passwords;
		};

		View.prototype = {
			renderContent: function() {
				var source = $('#content-tpl').html();
				var template = Handlebars.compile(source);
				//var html = template({password: this._passwords.getActive()});
				var html = template({
					passwords: this._passwords.getAll()
				});

				$('#PasswordsTableContent tbody').html(html);

				update_pwcount();

				FormatTable();

				// sort on website, reset sort first
				$('#column_id').click();
				$('#column_website').click();

				$('#PasswordsTableContent td').click(function() {
					var col = $(this).parent().children().index($(this));
					var row = $(this).parent().parent().children().index($(this).parent()) + 1;
					var table = document.getElementById('PasswordsTableContent');
					var website = table.rows[row].cells[0].textContent;
					var user = table.rows[row].cells[1].textContent;
					var db_id = table.rows[row].cells[10].textContent;
					if (col == 11) {
						// clicked on trash, ask confirmation
						if (confirm(t('passwords', "This will delete the password for") + " '" + website + "' " + t('passwords', "with user name") + " '" + user + "'. " + t('passwords', "Are you sure?"))) {

							var passwords = new Passwords(OC.generateUrl('/apps/passwords/passwords'));
							var view = new View(passwords);
							passwords.removeByID(db_id).done(function() {

								// spinning wheel
								$('.icon-loading-dark').css('display', 'block');
								$('.icon-loading-dark').css('background-color', 'rgba(1, 1, 1, 0');
								$('#slideshow input').css('display', 'none');

								location.reload(true);

							}).fail(function() {
								alert(t('passwords', 'Error: Could not delete password.'));
							});
														
						}
					}

				});

								
			},
			renderNavigation: function() {
				
				// clear search field
				$('#search_clear').click(function() {
					$('#search_text').val('');
					$('#search_text').keyup();
				});

				// search function
				$('#search_text').keyup(function() {
					var $rows = $('#PasswordsTableContent tr').not('thead tr');
					var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

					// filter
					$rows.show().filter(function() {
						var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
						return !~text.indexOf(val);
					}).hide();
				});

				// clean up website: https://www.Google.com -> google.com
				$('#new_website').focusout(function() {
					$('#new_website').val(strip_website(this.value));
				});

				// create a new password
				var self = this;
				$('#new_password_add').click(function() {

					if ($('#new_username').val() == '' || $('#new_website').val() == '' || $('#new_password').val() == '') {
						alert(t('passwords', 'Fill in the website, user name and password.'));
						return false;
					}

					var password = {
						loginname: $('#new_username').val(),
						website: $('#new_website').val(),
						pass: $('#new_password').val()
					};

					self._passwords.create(password).done(function() {

						// spinning wheel
						$('.icon-loading-dark').css('display', 'block');
						var bg = $('.icon-loading-dark').css('background-color');
						var disp = $('#slideshow input').css('display');
						$('.icon-loading-dark').css('background-color', 'rgba(1, 1, 1, 0');
						$('#slideshow input').css('display', 'none');

						location.reload(true);

						// $('#new_username').val('');
						// $('#new_website').val('');
						// $('#new_password').val('');
						// $('#new_password').val('');
						// $('#generate-strength').text('');
						// $('#generate-passwordtools').fadeOut(250);
						// $('#gen_length').val('25');

						// self.renderContent();

						// // spinning wheel
						// $('.icon-loading-dark').css('display', 'hidden');
						// $('.icon-loading-dark').css('background-color', bg);
						// $('#slideshow input').css('display', disp);

						// // give yellow color to new row and fade out, search for highest ID
						// var table = document.getElementById('PasswordsTableContent');
						// var fieldID;
						// var highestID = 0;
						// var highestRow;
						// for (var i = 1; i < table.rows.length; i++) {
						// 	for (var j = 0; j < table.rows[i].cells.length; j++)
						// 		fieldID = parseInt(table.rows[i].cells[10].textContent);
						// 		if (fieldID > highestID) {
						// 			highestID = fieldID;
						// 			highestRow = i;
						// 		}
						// }
						// var rows = $('tr', $('#PasswordsTableContent'));
						// rows.eq(highestRow).animate( { backgroundColor: '#ffa' }, 1000, function() {
						// 	$(this).animate( { backgroundColor: 'none' }, 3000);
						// });

					}).fail(function() {
						alert(t('passwords', 'Error: Could not create password.'));
					});

				});

				// calculate strength
				$("#new_password").keyup(function() {
					strength_str(this.value, false);
				});

				// select whole password when entering field
				$('#new_password').click(function() {
					this.select();
				});

				// generate password
				$('#new_generate').click(function() {

					// show options
					$('#generate-passwordtools').fadeIn(500);

					var lower_checked = $('#gen_lower').prop('checked');
					var upper_checked = $('#gen_upper').prop('checked');
					var numbers_checked = $('#gen_numbers').prop('checked');
					var special_checked = $('#gen_special').prop('checked');
					var length_filled = $('#gen_length').val();
					var generate_new = '';

					if (!isNumeric(length_filled) || length_filled.length == 0 || length_filled < 4) {
						alert(t('passwords', 'Fill in a valid number as length with a minimum of 4.'));
						return false;
					}
					if (!lower_checked && !upper_checked && !numbers_checked && !special_checked) {
						alert(t('passwords', 'Select at least one option to generate a password.'));
						return false;
					}

					// run
					generate_new = generatepw(lower_checked, upper_checked, numbers_checked, special_checked, length_filled);
					
					// calculate strength
					strength_str(generate_new, false);

					// fill in
					$('#new_password').val(generate_new);


				});

			},
			render: function() {
				this.renderNavigation();
				this.renderContent();
			}
		};


		var passwords = new Passwords(OC.generateUrl('/apps/passwords/passwords'));
		var view = new View(passwords);
		passwords.loadAll().done(function() {
			view.render();
		}).fail(function() {
			alert(t('passwords', 'Error: Could not load passwords.'));
		});

	});


})(OC, window, jQuery);

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
function strHasLower(str) {
	return str.toUpperCase() != str;
}
function strHasUpper(str) {
	return str.toLowerCase() != str;
}
function strHasNumber(str) {
	var regex = /\d/g;
	return regex.test(str);
}
function strHasSpecial(str) {

	var number;

	for (i = 0; i < str.length; i++) {
	
		number = 0;
		number = str.substring(i, i + 1).charCodeAt(0);

		switch(true) {
			case number === 33:
			case number >= 35 && number <= 36:
			case number === 38:
			case number >= 40 && number <= 41:
			case number === 43:
			case number >= 45 && number <= 47:
			case number >= 58 && number <= 60:
			case number >= 62 && number <= 64:
			case number === 95:
				return true;
				break;
		}

	}

	// no special chars
	return false;
	
}

function FormatTable() {

	var table = document.getElementById('PasswordsTableContent');

	if (table != null) {

		var has_lower;
		var has_upper;
		var has_number;
		var has_special;

		for (var i = 1; i < table.rows.length; i++) {
			for (var j = 0; j < table.rows[i].cells.length; j++)

				var fieldPassword = table.rows[i].cells[2].textContent;
				if(strHasLower(fieldPassword)) {
					table.rows[i].cells[5].textContent = t('passwords', 'Yes');
					table.rows[i].cells[5].className = 'green';
				} else {
					table.rows[i].cells[5].textContent = t('passwords', 'No');
					table.rows[i].cells[5].className = 'red';
				}
				if(strHasUpper(fieldPassword)) {
					table.rows[i].cells[6].textContent = t('passwords', 'Yes');
					table.rows[i].cells[6].className = 'green';
				} else {
					table.rows[i].cells[6].textContent = t('passwords', 'No');
					table.rows[i].cells[6].className = 'red';
				}
				if(strHasNumber(fieldPassword)) {
					table.rows[i].cells[7].textContent = t('passwords', 'Yes');
					table.rows[i].cells[7].className = 'green';
				} else {
					table.rows[i].cells[7].textContent = t('passwords', 'No');
					table.rows[i].cells[7].className = 'red';
				}
				if(strHasSpecial(fieldPassword)) {
					table.rows[i].cells[8].textContent = t('passwords', 'Yes');
					table.rows[i].cells[8].className = 'green';
				} else {
					table.rows[i].cells[8].textContent = t('passwords', 'No');
					table.rows[i].cells[8].className = 'red';
				}

				// strength
				var pass_str = table.rows[i].cells[2].textContent;
				var strength_int = strength_func(pass_str);
				table.rows[i].cells[3].textContent = strength_int;
				table.rows[i].cells[3].textContent = t('passwords', strength_str(pass_str, true)) + ' (' + strength_int + ')';
				table.rows[i].cells[3].setAttribute('sorttable_customkey', 1 / strength_int);
				switch (strength_str(pass_str, true)) {
					case t('passwords', 'Weak'):
						table.rows[i].cells[3].className = 'red';
						table.rows[i].cells[2].className += ' red';
						break;
					case t('passwords', 'Moderate'):
						table.rows[i].cells[3].className = 'orange';
						table.rows[i].cells[2].className += ' orange';
						break;
					case t('passwords', 'Strong'):
						table.rows[i].cells[3].className = 'green';
						table.rows[i].cells[2].className += ' green';
						break;
				}

				// length
				var length;
				length = table.rows[i].cells[2].textContent.length;
				table.rows[i].cells[4].textContent = length;
				table.rows[i].cells[4].setAttribute('sorttable_customkey', 1 / length);

				// date
				var datePart = table.rows[i].cells[9].textContent.split('-');
				var dateToday = new Date();
				var dateRowEntry = new Date(table.rows[i].cells[9].textContent);
				// colorize date
				var diffInDays = Math.floor((dateToday - dateRowEntry) / (1000*60*60*24));
				var thisClass = table.rows[i].cells[3].className;
				if(diffInDays > 364) {
					table.rows[i].cells[9].className += ' red'; // > 365 dagen
					table.rows[i].cells[2].className += ' red'; // force red color on password
				} else if(diffInDays > 149) {
					table.rows[i].cells[9].className += ' orange'; // 150-364 days
					if (thisClass == 'green') {
						table.rows[i].cells[2].className += ' orange'
					}
				} else if(diffInDays < 150) {
					table.rows[i].cells[9].className += ' green'; // < 150 days
				}
				switch (diffInDays) {
					case 0:
						table.rows[i].cells[9].setAttribute('title', t('passwords', 'today'));
						break;
					case 1:
						if (t('passwords', 'lang_en') == 'lang_es') {
							table.rows[i].cells[9].setAttribute('title', ' hace ' + diffInDays + ' ' + t('passwords', 'day ago'));
						} else {
							table.rows[i].cells[9].setAttribute('title', diffInDays + ' ' + t('passwords', 'day ago'));
						}
						break;
					default:
						if (t('passwords', 'lang_en') == 'lang_es') {
							table.rows[i].cells[9].setAttribute('title', ' hace ' + diffInDays + ' ' + t('passwords', 'days ago'));
						} else {
							table.rows[i].cells[9].setAttribute('title', diffInDays + ' ' + t('passwords', 'days ago'));
						}
				}

				// goed laten sorteren (YYYYMMDD) en goed laten weergeven (D MMMM YYYY)
				var YYYYMMDD = datePart[0].toString() + datePart[1].toString() + datePart[2].toString();
				table.rows[i].cells[9].setAttribute('sorttable_customkey', YYYYMMDD);

				var Month;
				switch (Math.floor(datePart[1])) {
					case 1:
						Month = t('passwords', 'January');
						break;
					case 2:
						Month = t('passwords', 'February');
						break;
					case 3:
						Month = t('passwords', 'March');
						break;
					case 4:
						Month = t('passwords', 'April');
						break;
					case 5:
						Month = t('passwords', 'May');
						break;
					case 6:
						Month = t('passwords', 'June');
						break;
					case 7:
						Month = t('passwords', 'July');
						break;
					case 8:
						Month = t('passwords', 'August');
						break;
					case 9:
						Month = t('passwords', 'September');
						break;
					case 10:
						Month = t('passwords', 'October');
						break;
					case 11:
						Month = t('passwords', 'November');
						break;
					case 12:
						Month = t('passwords', 'December');
						break;
				}

				if (t('passwords', 'lang_en') == 'lang_en') {
					// format: 14th March 2011, most Brittish according to https://www.englishclub.com/vocabulary/time-date.htm
					var suffix;
					switch (Math.floor(datePart[2])) {
						case 1:
						case 21:
						case 31:
							suffix = 'st';
							break;
						case 2:
						case 22:
							suffix = 'nd';
							break;
						case 3:
						case 23:
							suffix = 'rd';
							break;
						default:
							suffix = 'th';
							break;
					}
					table.rows[i].cells[9].innerHTML = Math.floor(datePart[2]) + '<sup>' + suffix + '</sup> ' + Month + ' ' + datePart[0];
				} else if (t('passwords', 'lang_en') == 'lang_nl') {
					// Dutch: 14 maart 2015
					table.rows[i].cells[9].innerHTML = Math.floor(datePart[2]) + ' ' + Month + ' ' + datePart[0];
				} else if (t('passwords', 'lang_en') == 'lang_de') {
					// German: 14. März 2015
					table.rows[i].cells[9].innerHTML = Math.floor(datePart[2]) + '. ' + Month + ' ' + datePart[0];
				} else if (t('passwords', 'lang_en') == 'lang_es') {
					// Spanish: 14 de marzo de 2015
					table.rows[i].cells[9].innerHTML = Math.floor(datePart[2]) + ' de ' + Month + ' de ' + datePart[0];
				
				} else {
					table.rows[i].cells[9].innerHTML = Math.floor(Month + ' ' + datePart[2]) + ', ' + datePart[0];
				}

				table.rows[i].cells[9].innerHTML = "<span>" + table.rows[i].cells[9].innerHTML + "</span>";

		}

		// user and password clickable
		for (var i = 1; i < table.rows.length; i++) {
			for (var j = 0; j < table.rows[i].cells.length; j++)

				var cellWebsite = table.rows[i].cells[0];

				// clickable website
				if(isUrl(cellWebsite.textContent)) {
					cellWebsite.innerHTML = '<a href="http://' + cellWebsite.textContent + '" target="_blank"><img class="websitepic" src="https://www.google.com/s2/favicons?domain=http://www.' + cellWebsite.textContent + '">' + cellWebsite.textContent + '</a>';
					cellWebsite.className += 'is_website';
				} else {
					cellWebsite.innerHTML = cellWebsite.textContent; // or else doesn't align very well
				}			

				table.rows[i].cells[1].onclick = function () { // user name
					window.prompt("", this.textContent);	
				};
				table.rows[i].cells[2].onclick = function () { //password
					window.prompt("", this.textContent);
				};
		}

	}
}

function isUrl(url) {

	// not starting with a whitespace char or / or $ or . or ? or #
	// overall no spaces allowed
	// at least 1 char before and 2 chars after a dot
	var strRegex = '^[^\\s/$.?#]\\S{1,}\\.\\S{2,}$';
	var re = new RegExp(strRegex);

	return re.test(url);
 }

function strength_func(Password) {

	var charInStr;
	var strength_calc;
	var passwordLength;
	var hasLowerCase;
	var hasUpperCase;
	var hasNumber;
	var hasSpecialChar1;
	var hasSpecialChar2;
	var hasSpecialChar3;
	var hasSpecialChar4;
	var charInt;
 
	passwordLength = Password.length;

	strength_calc = 0;

	// check length
	switch(true) {
		case passwordLength >= 8:
			strength_calc = 1;
			break;
		case passwordLength <= 4:
			// password smaller than 5 chars is always bad
			return 0;
			break;
	}

	// loop ONCE through password
	for (i = 1; i < passwordLength + 1; i++) {
		
		charInStr = Password.slice(i, i + 1);
		charInt = charInStr.charCodeAt(0);

		switch(true) {
			case charInt >= 97 && charInt <= 122:
				if (!hasLowerCase) {
					strength_calc = strength_calc + 1;
					hasLowerCase = true;
				}
				break;
			case charInt >= 65 && charInt <= 90:
				if (!hasUpperCase) {
					strength_calc = strength_calc + 1;
					hasUpperCase = true;
				}
				break;
			case charInt >= 48 && charInt <= 57:
				if (!hasNumber) {
					strength_calc = strength_calc + 1;
					hasNumber = true;
				}
				break;
			case charInt >= 33 && charInt <= 47:
				if (!hasSpecialChar1) {
					strength_calc = strength_calc + 1;
					hasSpecialChar1 = true;
				}
				break;
			case charInt >= 58 && charInt <= 64:
				if (!hasSpecialChar2) {
					strength_calc = strength_calc + 1;
					hasSpecialChar2 = true;
				}
				break;
			case charInt >= 91 && charInt <= 96:
				if (!hasSpecialChar3) {
					strength_calc = strength_calc + 1;
					hasSpecialChar3 = true;
				}
				break;
			case charInt >= 123 && charInt <= 255:
				if (!hasSpecialChar4) {
					strength_calc = strength_calc + 1;
					hasSpecialChar4 = true;
				}
				break;
		}

	}
	
	strength_calc = strength_calc + (Math.floor(passwordLength / 8) * ((hasLowerCase ? 1 : 0) + (hasUpperCase ? 1 : 0) + (hasNumber ? 1 : 0) + (hasSpecialChar1 ? 1 : 0) + (hasSpecialChar2 ? 1 : 0) + (hasSpecialChar3 ? 1 : 0) + (hasSpecialChar4 ? 1 : 0)));
	
	return strength_calc;

}

function generatepw(lower, upper, number, special, length_chars) {

	var length_calc = Math.floor(length_chars / (lower + upper + number + special));

	var Wlower = "";
	var Wupper = "";
	var Wnumber = "";
	var Wspecial = "";

	if (lower) {
		Wlower = random_characters(0, length_calc);
	}
	if (upper) {
		Wupper = random_characters(1, length_calc);
	}
	if (number) {
		Wnumber = random_characters(2, length_calc);
	}
	if (special) {
		Wspecial = random_characters(3, length_calc);
	}

	var ww = "" + Wlower + Wupper + Wnumber + Wspecial;

	// e.g. length 27 with all 4 options = 6 char for every option (24) so 3 remaining
	// so fill up, starting with special, then number, then upper, then lower:
	var difference = length_chars - length_calc * (lower + upper + number + special);
	if (special) {
		ww = ww + random_characters(3, difference);
	} else if (number) {
		ww = ww + random_characters(2, difference);
	} else if (upper) {
		ww = ww + random_characters(1, difference);
	} else if (lower) {
		ww = ww + random_characters(0, difference);
	}

	// do a Fisher-Yates shuffle
	var a = ww.split("");
	var n = a.length;

	for (var i = n - 1; i > 0; i--) {
		var j = Math.floor(Math.random() * (i + 1));
		var tmp = a[i];
		a[i] = a[j];
		a[j] = tmp;
	}

	ww = a.join("");

	return ww;

}

function random_characters(char_kind, size_wanted) {

	var allowed = "";
	var text = "";

	switch (char_kind) {
		// No | l I 1 B 8 0 O o due to reading ability
		case 0:
			allowed = "abcdefghijkmnpqrstuvwxyz";
			break;
		case 1:
			allowed = "ACDEFGHJKLMNPQRSTUVWXYZ";
			break;
		case 2:
			allowed = "2345679";
			break;
		case 3:
			allowed = "!@#$%^&*()_+~[]{}:;?><,./-=";
			break;
	}

	for (var i = 0; i < size_wanted; i++)
	text += allowed.charAt(Math.floor(Math.random() * allowed.length));

	return text;
}

function strength_str(passw, return_string_only) {

	if (!return_string_only) {
		if (passw == '') { 
			$("#generate-strength").text(''); 
			return false;
		}

		$("#generate-strength").removeClass("red");
		$("#generate-strength").removeClass("orange");
		$("#generate-strength").removeClass("green");
	}
	
	switch (strength_func(passw)) {
		case 0:
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
			if (return_string_only) { return t('passwords', 'Weak'); }
			$("#generate-strength").text(t('passwords', 'Strength') + ': ' + t('passwords', 'Weak').toLowerCase() + ' (' + strength_func(passw) + ')');
			$("#generate-strength").addClass("red");
			break;
		case 8:
		case 9:
		case 10:
		case 11:
		case 12:
		case 13:
		case 14:
			if (return_string_only) { return t('passwords', 'Moderate'); }
			$("#generate-strength").text(t('passwords', 'Strength') + ': ' + t('passwords', 'Moderate').toLowerCase() + ' (' + strength_func(passw) + ')');
			$("#generate-strength").addClass("orange");
			break;
		default: // everything >= 15
			if (return_string_only) { return t('passwords', 'Strong'); }
			$("#generate-strength").text(t('passwords', 'Strength') + ': ' + t('passwords', 'Strong').toLowerCase() + ' (' + strength_func(passw) + ')');
			$("#generate-strength").addClass("green");
	}
}

function update_pwcount() {
	var count = $('#PasswordsTableContent tbody tr').length;
	$(".menu_passwords_active").text(count);
	if (count == 0) {
		$("#emptycontent").show();
		$("#PasswordsTable").hide();
	}
}

function strip_website(website) {

	var convert = website;

	if (!isUrl(website)) {
		return website;
	}
	
	if (convert.substr(0, 8) == "https://") {
		convert = convert.substr(8, convert.length - 8);
	};

	if (convert.substr(0, 7) == "http://") {
		convert = convert.substr(7, convert.length - 7);
	};
	
	if (convert.substr(0, 4) == "www.") {
		convert = convert.substr(4, convert.length - 4);
	};

	return convert;
}
