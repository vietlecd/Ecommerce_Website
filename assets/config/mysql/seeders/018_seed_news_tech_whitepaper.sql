-- Seeder 018: Tech Whitepaper
INSERT INTO `news` (`Title`, `Description`, `Content`, `AdminID`, `news_type`, `promotion_id`, `thumbnail`)
VALUES (
    'Whitepaper: Sensor-Enabled Footwear Ecosystem',
    'Technical breakdown of integrating pressure sensors, firmware, and cloud dashboards.',
    '<h2>System Architecture</h2>
     <p>Sensors embedded in insoles capture pressure, gait, and temperature data. Firmware aggregates into encrypted packets.</p>
     <h2>Data Pipeline</h2>
     <ul>
        <li>Bluetooth Low Energy transmission</li>
        <li>Edge preprocessing on the app</li>
        <li>Cloud ingestion via MQTT</li>
     </ul>
     <h2>Use Cases</h2>
     <p>Injury prevention dashboards, dynamic cushioning adjustments, and personalized training cues.</p>
     <h2>Security + Privacy</h2>
     <p>All identifiers hashed, opt-in consent screens, and data deletion flows built into settings.</p>
     <h2>Roadmap</h2>
     <p>Next iterations include haptic alerts, AI-driven rotation suggestions, and open APIs for third-party coaches.</p>',
    7,
    'whitepaper',
    NULL,
    'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1200&q=80'
);

