(async function () {
    window.addEventListener('load', async function () {
        try {
            console.info('Start Visit Tracking!');
            // Get the client's IP address
            const ipResponse = await fetch('https://api.ipify.org?format=json');
            const ipData = await ipResponse.json();
            const ip = ipData.ip;

            // Get the location
            const geoResponse = await fetch('https://ipapi.co/' + ip + '/json/');
            const geoData = await geoResponse.json();
            const country = geoData.country_name ? geoData.country_name : '';
            const city = geoData.city ? geoData.city : '';

            const protocol = window.location.protocol;
            const host = window.location.host;
            const pathname = window.location.pathname;
            const baseUrl = protocol + '//' + host + pathname;

            const userAgent = navigator.userAgent;
            const visitedPage = window.location.href;
            const referrer = document.referrer;
            const device = ''; // https://www.npmjs.com/package/mobile-detect
            const screenResolution = `${window.screen.width}x${window.screen.height}`;
            const browser = getBrowserName();
            const browserVersion = navigator.appVersion;
            const operatingSystem = getOperatingSystem();

            const requestData = {
                ip_address: ip,
                user_agent: userAgent,
                visited_page: visitedPage,
                referrer: referrer,
                country,
                city,
                device: device,
                screen_resolution: screenResolution,
                browser: browser,
                browser_version: browserVersion,
                operating_system: operatingSystem,
            };

            // URL of the tracking endpoint
            const apiUrl = baseUrl + 'visit-tracking';

            await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });

            console.info('Visit Tracking Finished!');
        } catch (error) {
            console.error('Visit Tracking failed: ', error);
        }
    });
})();

// Function to get the browser name
function getBrowserName() {
    const userAgent = navigator.userAgent;
    if (userAgent.indexOf('Firefox') > -1) {
        return 'Firefox';
    } else if (userAgent.indexOf('Chrome') > -1) {
        return 'Chrome';
    } else if (userAgent.indexOf('Safari') > -1) {
        return 'Safari';
    } else if (userAgent.indexOf('Opera') > -1 || userAgent.indexOf('OPR') > -1) {
        return 'Opera';
    } else if (userAgent.indexOf('Edge') > -1) {
        return 'Edge';
    } else if (userAgent.indexOf('MSIE') > -1 || !!document.documentMode) {
        return 'Internet Explorer';
    } else {
        return 'Unknown';
    }
}

// Function for obtaining the client's operating system
function getOperatingSystem() {
    const userAgent = navigator.userAgent;
    if (userAgent.indexOf('Windows') > -1) {
        return 'Windows';
    } else if (userAgent.indexOf('Macintosh') > -1) {
        return 'Macintosh';
    } else if (userAgent.indexOf('Linux') > -1) {
        return 'Linux';
    } else if (userAgent.indexOf('Android') > -1) {
        return 'Android';
    } else if (userAgent.indexOf('iOS') > -1) {
        return 'iOS';
    } else {
        return 'Unknown';
    }
}
