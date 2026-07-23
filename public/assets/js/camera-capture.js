/**
 * GCTU Camera Capture Utility
 */
class GCTUCamera {
    constructor(containerId, videoId, previewId, hiddenInputId) {
        this.container = document.querySelector(containerId);
        this.video = document.querySelector(videoId);
        this.preview = document.querySelector(previewId);
        this.hiddenInput = document.querySelector(hiddenInputId);
        this.stream = null;
        this.canvas = document.createElement('canvas');
    }

    async start() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user", width: 640, height: 480 }
            });
            this.video.srcObject = this.stream;
            this.container.style.display = 'block';
            this.video.style.display = 'block';
            this.preview.style.display = 'none';
            return true;
        } catch (err) {
            console.error("Error accessing camera:", err);
            alert("Could not access camera. Please ensure you have granted permission.");
            return false;
        }
    }

    stop() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        this.container.style.display = 'none';
    }

    capture() {
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;
        const ctx = this.canvas.getContext('2d');
        ctx.drawImage(this.video, 0, 0);
        
        const dataUrl = this.canvas.toDataURL('image/jpeg', 0.9);
        this.hiddenInput.value = dataUrl;
        
        this.preview.src = dataUrl;
        this.preview.style.display = 'block';
        this.video.style.display = 'none';
    }

    retake() {
        this.hiddenInput.value = '';
        this.preview.style.display = 'none';
        this.video.style.display = 'block';
    }
}

// Global initialization for modals
window.initCameraCapture = function(config) {
    const camera = new GCTUCamera(
        config.container,
        config.video,
        config.preview,
        config.input
    );

    const startBtn = document.querySelector(config.startBtn);
    const captureBtn = document.querySelector(config.captureBtn);
    const retakeBtn = document.querySelector(config.retakeBtn);
    const uploadInput = document.querySelector(config.uploadInput);
    const options = document.querySelectorAll(config.optionTabs);

    options.forEach(opt => {
        opt.addEventListener('click', function() {
            options.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            
            const mode = this.dataset.mode;
            if (mode === 'camera') {
                config.uploadContainer && (document.querySelector(config.uploadContainer).style.display = 'none');
                camera.start();
            } else {
                camera.stop();
                config.uploadContainer && (document.querySelector(config.uploadContainer).style.display = 'block');
            }
        });
    });

    captureBtn.addEventListener('click', () => camera.capture());
    retakeBtn.addEventListener('click', () => camera.retake());

    return camera;
};
