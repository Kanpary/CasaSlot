// geeGuard bypass functionality
window.geeGuard = window.geeGuard || {};
window.geeGuard.bypass = function() {
    return true;
};

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.geeGuard;
}