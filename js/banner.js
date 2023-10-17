$(document).ready(function() {
	setInterval(function() {checkTimer()}, 5000)

	$(document).on('click', '#banner .close-banner', function () {
		hideBanner()
		localStorage.setItem('bannerTimer', + new Date())
	})
})

function checkTimer() {
	if ((Number(localStorage.getItem('bannerTimer')) + 600000) < (+ new Date())) {
		showBanner()
	}
}

function showBanner() {
	$('#banner').css('display','flex')
}

function hideBanner() {
	$('#banner').css('display','none')
}