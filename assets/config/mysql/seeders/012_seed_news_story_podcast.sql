-- Seeder 012: Story + Podcast Transcript
INSERT INTO `news` (`Title`, `Description`, `Content`, `AdminID`, `news_type`, `promotion_id`, `thumbnail`)
VALUES (
    'Podcast Transcript: Behind the Outsole Episode 12',
    'Full transcript of our design podcast featuring the Outsole Engineering Lead.',
    '<h2>Intro</h2>
     <p>Host Maya chats with Carlos Alvarez, the engineer behind our new cushioning system.</p>
     <h2>Segment 1 &mdash; Childhood Inspirations</h2>
     <p>Carlos grew up sketching rocket boosters. That fascination translated into sculpted midsole geometries.</p>
     <h2>Segment 2 &mdash; Prototyping Chaos</h2>
     <p>The lab produced 63 variants. Some collapsed under heat lamps, others bounced like trampolines.</p>
     <h2>Segment 3 &mdash; Community Testing</h2>
     <ul>
        <li>Blind wear tests with marathoners</li>
        <li>Pressure-mapping sessions using force plates</li>
        <li>Feedback loops through our Discord server</li>
     </ul>
     <h2>Segment 4 &mdash; What''s Next</h2>
     <p>Expect custom-tuned inserts, NFC chips tracking wear, and sound-reactive reflective paints.</p>
     <h2>Outro</h2>
     <p>Stream the full episode, download the blueprint PDF, and join the next live AMA.</p>',
    4,
    'podcast',
    NULL,
    'https://images.unsplash.com/photo-1475724017904-b712052c192a?auto=format&fit=crop&w=1200&q=80'
);

