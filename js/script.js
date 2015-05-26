(function (OC, window, $, undefined) {
'use strict';

$(document).ready(function () {

var translations = {
    newPassword: $('#new-password-string').text()
};

// this passwords object holds all our passwords
var Passwords = function (baseUrl) {
    this._baseUrl = baseUrl;
    this._passwords = [];
    this._activePassword = undefined;
};

Passwords.prototype = {
    load: function (id) {
        var self = this;
        this._passwords.forEach(function (password) {
            if (password.id === id) {
                password.active = true;
                self._activePassword = password;
            } else {
                password.active = false;
            }
        });
    },
    getActive: function () {
        return this._activePassword;
    },
    removeActive: function () {
        var index;
        var deferred = $.Deferred();
        var id = this._activePassword.id;
        this._passwords.forEach(function (password, counter) {
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
            }).done(function () {
                deferred.resolve();
            }).fail(function () {
                deferred.reject();
            });
        } else {
            deferred.reject();
        }
        return deferred.promise();
    },
    create: function (password) {
        var deferred = $.Deferred();
        var self = this;
        $.ajax({
            url: this._baseUrl,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(password)
        }).done(function (password) {
            self._passwords.push(password);
            self._activePassword = password;
            self.load(password.id);
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
        return deferred.promise();
    },
    getAll: function () {
        return this._passwords;
    },
    loadAll: function () {
        var deferred = $.Deferred();
        var self = this;
        $.get(this._baseUrl).done(function (passwords) {
            self._activePassword = undefined;
            self._passwords = passwords;
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
        return deferred.promise();
    },
    updateActive: function (title, content) {
        var password = this.getActive();
        password.title = title;
        password.content = content;

        return $.ajax({
            url: this._baseUrl + '/' + password.id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(password)
        });
    }
};

// this will be the view that is used to update the html
var View = function (passwords) {
    this._passwords = passwords;
};

View.prototype = {
    renderContent: function () {
        var source = $('#content-tpl').html();
        var template = Handlebars.compile(source);
        var html = template({password: this._passwords.getActive()});

        $('#editor').html(html);

        // handle saves
        var textarea = $('#app-content textarea');
        var self = this;
        $('#app-content button').click(function () {
            var content = textarea.val();
            var title = content.split('\n')[0]; // first line is the title

            self._passwords.updateActive(title, content).done(function () {
                self.render();
            }).fail(function () {
                alert('Could not update password, not found');
            });
        });
    },
    renderNavigation: function () {
        var source = $('#navigation-tpl').html();
        var template = Handlebars.compile(source);
        var html = template({passwords: this._passwords.getAll()});

        $('#app-navigation ul').html(html);

        // create a new password
        var self = this;
        $('#new-password').click(function () {
            var password = {
                title: translations.newPassword,
                content: ''
            };

            self._passwords.create(password).done(function() {
                self.render();
                $('#editor textarea').focus();
            }).fail(function () {
                alert('Could not create password');
            });
        });

        // show app menu
        $('#app-navigation .app-navigation-entry-utils-menu-button').click(function () {
            var entry = $(this).closest('.password');
            entry.find('.app-navigation-entry-menu').toggleClass('open');
        });

        // delete a password
        $('#app-navigation .password .delete').click(function () {
            var entry = $(this).closest('.password');
            entry.find('.app-navigation-entry-menu').removeClass('open');

            self._passwords.removeActive().done(function () {
                self.render();
            }).fail(function () {
                alert('Could not delete password, not found');
            });
        });

        // load a password
        $('#app-navigation .password > a').click(function () {
            var id = parseInt($(this).parent().data('id'), 10);
            self._passwords.load(id);
            self.render();
            $('#editor textarea').focus();
        });
    },
    render: function () {
        this.renderNavigation();
        this.renderContent();
    }
};

var passwords = new Passwords(OC.generateUrl('/apps/passwords/passwords'));
var view = new View(passwords);
passwords.loadAll().done(function () {
    view.render();
}).fail(function () {
    alert('Could not load passwords');
});


});

})(OC, window, jQuery);