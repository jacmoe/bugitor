$(document).ready(function() {
    $.cookiesDirective({
      backgroundOpacity: '85',
      backgroundColor: '#1E6887',
      linkColor: '#FFF',
      position: 'bottom',
      explicitConsent: false,
      message: 'This website uses cookies for persistent logins and other site functionality. By visiting this site you accept the use of cookies.',
      privacyPolicyUri: '/site/cookies'
    });
});
