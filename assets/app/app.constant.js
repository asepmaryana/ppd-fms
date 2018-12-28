angular.module('app.constant', [])
.constant('EVENTS', {
	notAuthenticated: 'auth-not-authenticated',
	notAuthorized: 'auth-not-authorized',
	sessionTimedout: 'session-time-out',
	internalError: 'internal-error',
	profileChanged: 'profile-changed',
	dashboardDisabled: 'dashboard-disabled',
	loginSucceed: 'login-succeed'
})
.constant('USER_ROLES', {
	OPR: '1',
	ADM: '2'	
})
;