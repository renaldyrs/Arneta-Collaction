/**
 * Arneta POS — QZ Tray Bridge
 * Provides direct thermal printing and hardware control.
 */

const ArnetaPrinter = {
    connected: false,
    printerName: null,

    /**
     * Initialize connection to QZ Tray
     */
    async connect(printerName = null) {
        if (this.connected) return true;
        this.printerName = printerName;

        try {
            if (!qz.websocket.isActive()) {
                await qz.websocket.connect();
            }
            this.connected = true;
            console.log("QZ Tray Connected");
            return true;
        } catch (err) {
            console.error("QZ Tray Connection Error:", err);
            return false;
        }
    },

    /**
     * Print HTML content via QZ Tray
     */
    async printHtml(url, options = {}) {
        const isConnected = await this.connect(options.printerName || this.printerName);
        if (!isConnected) {
            console.warn("Direct print failed: QZ Tray not connected. Falling back to browser print.");
            return false;
        }

        try {
            const config = qz.configs.create(options.printerName || this.printerName);
            
            // Prepare printing data
            let data = [
                {
                    type: 'pixel',
                    format: 'html',
                    flavor: 'url',
                    data: url,
                    options: { 
                        pageWidth: options.width || 80,
                        pageHeight: options.height || null
                    }
                }
            ];

            // Add Auto-Cut command if requested
            if (options.autoCut) {
                // ESC/POS Cut Command: GS V 66 0 (0x1D 0x56 0x42 0x00)
                data.push({ type: 'raw', format: 'command', flavor: 'hex', data: '1D564200' });
            }

            await qz.print(config, data);
            return true;
        } catch (err) {
            console.error("QZ Print Error:", err);
            return false;
        }
    },

    /**
     * Send Raw ESC/POS Commands
     */
    async sendRaw(commands, printerName = null) {
        const isConnected = await this.connect(printerName || this.printerName);
        if (!isConnected) return false;

        try {
            const config = qz.configs.create(printerName || this.printerName);
            await qz.print(config, [{ type: 'raw', format: 'command', flavor: 'plain', data: commands }]);
            return true;
        } catch (err) {
            console.error("Raw Print Error:", err);
            return false;
        }
    }
};

window.ArnetaPrinter = ArnetaPrinter;
