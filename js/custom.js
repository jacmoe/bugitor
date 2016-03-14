$(document).ready(function() {
    $.cookiesDirective({
      backgroundOpacity: '85',
      backgroundColor: '#1E6887',
      linkColor: '#FFF',
      position: 'bottom',
      explicitConsent: false,
      message: 'This website uses cookies for analytics and persistent logins. By visiting this site you accept the use of cookies.',
      privacyPolicyUri: 'cookies'
    });
});
