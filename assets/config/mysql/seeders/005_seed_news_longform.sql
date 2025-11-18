-- Seeder: Longform editorial news content
-- Description: Adds detailed HTML-rich blog articles for news module

INSERT INTO `news` (`Title`, `Description`, `Content`, `AdminID`, `news_type`, `promotion_id`, `thumbnail`)
VALUES
(
    'Field Notes: Building the Perfect Travel Rotation',
    'A three-part blueprint covering packing strategy, maintenance, and styling tips for globetrotters.',
    '<h2>Chapter 1 &mdash; Packing Framework</h2>
     <p>Start with a <strong>3-2-1 rule</strong>: three versatile sneakers, two weatherproof options, and one elegant dress shoe.
     Use compression cubes to keep soles separated from apparel, and slip cedar sachets into each pair.</p>
     <h3>Checklist</h3>
     <ul>
        <li>Midsole wipes + microfiber cloth</li>
        <li>Travel-size neutral conditioner</li>
        <li>Fold-flat shoe trees</li>
     </ul>
     <h2>Chapter 2 &mdash; On-The-Go Care</h2>
     <p>Create a nightly ritual: brush off dust, air them near ventilation, and rotate insoles every other wear.</p>
     <h2>Chapter 3 &mdash; Style Capsules</h2>
     <p>Anchor each outfit with a hero pair. Suede loafers elevate linen sets, while trail runners sharpen athleisure uniforms.</p>',
    3,
    'editorial',
    NULL,
    'https://images.unsplash.com/photo-1520970014086-2208d157c9e2?auto=format&fit=crop&w=1200&q=80'
),
(
    'Material Science: Inside Our Sustainable Workshop',
    'An in-depth look at recycled fabrics, bio-based foams, and the artisans behind the line.',
    '<h2>Lab Innovations</h2>
     <p>The R&D team spent 18 months prototyping <strong>bio-foam</strong> midsoles that reduce petrochemical usage by 42%.</p>
     <h3>Key Materials</h3>
     <ol>
        <li>Pineapple leather quarters</li>
        <li>Algae-based EVA blend</li>
        <li>Recycled ocean plastics for laces</li>
     </ol>
     <h2>Meet the Makers</h2>
     <p>Artisans in Da Nang stitch every panel by hand, leveraging a closed-loop water system.</p>
     <blockquote>&ldquo;Craftmanship and sustainability are not mutually exclusive.&rdquo; &mdash; Lead Designer Amelie Vu</blockquote>
     <h2>How You Can Support</h2>
     <p>Opt into our refurbishment program and track each product''s lifecycle via the NFC chip under the tongue.</p>',
    4,
    'story',
    NULL,
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'
),
(
    'Playbook: Maximizing Flash Sale Wins',
    'Data-backed tactics, shopping checklists, and automation tips for limited flash events.',
    '<h2>Pre-Game Setup</h2>
     <p>Sync calendar reminders, preload payment options, and pin your top five SKUs inside the wishlist.</p>
     <h3>Automation Stack</h3>
     <ul>
        <li>Browser profiles for each family member</li>
        <li>Auto-fill scripts tested 24 hours prior</li>
        <li>Push notifications routed to smartwatch</li>
     </ul>
     <h2>Real-Time Execution</h2>
     <p>Log in 15 minutes early, monitor the live inventory ticker, and prioritize sizes with fewer than 15 units.</p>
     <h3>Post-Game Checklist</h3>
     <p>Confirm tracking numbers, document serial codes for authenticity, and share hauls using #ShoeStorePlaybook.</p>',
    2,
    'guide',
    6,
    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80'
);

