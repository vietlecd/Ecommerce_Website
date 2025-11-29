-- Seeder: Initial data for site_contents table
-- Date: 2025-01-XX
-- Description: Insert default HTML content for About Us and Q&A pages

-- Insert default About Us page content
INSERT INTO `site_contents` (`page_key`, `html_content`) VALUES
('about', 
'<section class="about-hero">
    <div class="about-hero-overlay"></div>
    <div class="about-hero-content">
        <p class="eyebrow">Sculpting Footwear Since 2012</p>
        <h1>We are V.AShoes</h1>
        <p>Obsessed with the craft, devoted to the community, fueled by the belief that a pair of shoes can change posture, perspective, and possibility.</p>
    </div>
</section>

<section class="about-intro container">
    <div class="intro-grid">
        <article class="intro-card">
            <h2>Our Manifesto</h2>
            <p><strong>V.AShoes</strong> was born under the flickering light of a small workshop where the smell of veg-tan leather mingled with laughter, late-night espresso, and punk playlists. We pledged to build a company that celebrates <em>materials</em>, <em>movement</em>, and <em>meaningful rituals</em>. We reject fast fades. We embrace deliberate design, small-batch production, and transparent storytelling.</p>
            <p>Every silhouette we release begins with a conversation. We interview athletes, choreographers, commuters, museum docents, and anyone whose day is measured in steps. Their anecdotes shape our sketches. Their frustrations steer our iterations. By the time a pair leaves our studio, it carries the fingerprints of dozens of collaborators who will probably never meet, yet somehow move together.</p>
            <p>We extend an open invitation to anyone who values craft. Whether you geek out over stitch density, crave limited runs, or simply want a shoe that can sprint through a rainstorm and still look poetic, V.AShoes is for you.</p>
        </article>
        <aside class="intro-card highlight">
            <h3>What we obsess over</h3>
            <ul>
                <li><strong>Biomechanics:</strong> partnering with sports researchers so cushioning profiles honor natural gait cycles.</li>
                <li><strong>Material chemistry:</strong> combining algae foams, corn-based leathers, and recycled silver ions to deliver healthier interiors.</li>
                <li><strong>Human rituals:</strong> designing packaging that doubles as storage altars so shoes feel treasured, not disposable.</li>
                <li><strong>Long-form storytelling:</strong> each drop ships with a 24-page micro zine exploring the cultural references behind the palette.</li>
            </ul>
            <p class="note">These pillars are non-negotiable. They keep us grounded even when the industry chases gimmicks.</p>
        </aside>
    </div>
</section>

<section class="about-chronicle">
    <div class="container">
        <div class="section-header">
            <h2>Chronicle of Milestones</h2>
            <p>The path from garage experiment to global label was anything but linear. Explore the decisions, detours, and daring collaborations that shaped us.</p>
        </div>
        <div class="timeline-table-wrapper">
            <table class="timeline-table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Milestone</th>
                        <th>Impact</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Year">2012</td>
                        <td data-label="Milestone">Launch of the atelier in District 3 with three artisans, two sewing machines, and a relentless playlist of lo-fi beats.</td>
                        <td data-label="Impact">Set the tone: hand-numbered drops, zero unsold inventory, total transparency.</td>
                    </tr>
                    <tr>
                        <td data-label="Year">2014</td>
                        <td data-label="Milestone">Partnership with urban dancers to create the FlowForm sole.</td>
                        <td data-label="Impact">Introduced a cushioning system tuned for lateral expression, quickly adopted by street performers worldwide.</td>
                    </tr>
                    <tr>
                        <td data-label="Year">2016</td>
                        <td data-label="Milestone">First archive residency in Kyoto.</td>
                        <td data-label="Impact">Access to century-old textile swatches sparked our signature indigo-dipped lining.</td>
                    </tr>
                    <tr>
                        <td data-label="Year">2019</td>
                        <td data-label="Milestone">Launch of Loopback Program.</td>
                        <td data-label="Impact">Customers return worn pairs for refurbishment or recycling; 72% of materials stay in circulation.</td>
                    </tr>
                    <tr>
                        <td data-label="Year">2021</td>
                        <td data-label="Milestone">Immersive flagship in Fukuoka with foot-scan theater.</td>
                        <td data-label="Impact">Blends biometric fitting, projection mapping, and personal playlists to choreograph the try-on ritual.</td>
                    </tr>
                    <tr>
                        <td data-label="Year">2024</td>
                        <td data-label="Milestone">Global community studio network spanning Hanoi, São Paulo, Lagos, and Reykjavík.</td>
                        <td data-label="Impact">Local curators tell hyper-specific stories; each studio leads limited runs exclusive to their city.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="about-values container">
    <div class="section-header">
        <h2>Design Values</h2>
        <p>We summarize our philosophy in six verbs. Each verb guides sketches, sourcing, packaging, and the way we answer your emails at 2 a.m.</p>
    </div>
    <div class="values-grid">
        <article>
            <h3>Listen</h3>
            <p>Before we prototype, we sit quietly. Farmers describe muddy fields. Architects describe marble lobbies. Parents describe school runs. Listening turns anecdotes into material specs.</p>
        </article>
        <article>
            <h3>Distill</h3>
            <p>We slice away noise. Every line, perforation, and lace keep is there for a reason. Minimalism is not an aesthetic; it is respect for your time.</p>
        </article>
        <article>
            <h3>Prototype</h3>
            <p>We run hundreds of micro iterations. Some prototypes never leave the lab, yet each teaches us what the foot tolerates, celebrates, or rejects.</p>
        </article>
        <article>
            <h3>Empathize</h3>
            <p>We test in monsoon markets, frozen piers, lecture halls, and silent retreats. Shoes must adapt to widely different energies without judgment.</p>
        </article>
        <article>
            <h3>Educate</h3>
            <p>We open our notebooks. Customers see cost breakdowns, care rituals, and supply-chain audits. Knowledge dismantles the mystery around luxury pricing.</p>
        </article>
        <article>
            <h3>Celebrate</h3>
            <p>Every purchase funds live sessions with musicians, chefs, illustrators, and fragrance artists. We celebrate the creative ecosystems that inspire us.</p>
        </article>
    </div>
</section>

<section class="about-materials">
    <div class="container">
        <h2>Material Playbook</h2>
        <p>We obsess over composition. Each product spec sheet reads like a love letter to material science.</p>
        <div class="materials-layout">
            <article>
                <h3>Upper Laboratory</h3>
                <ul>
                    <li><strong>SeaSilk Canvas:</strong> woven from reclaimed oyster nets, finished with tea-dyed gradients.</li>
                    <li><strong>Mirage Suede:</strong> silicone-backed to repel rain without losing the dreamy nap.</li>
                    <li><strong>Shadow Mesh:</strong> heat-reactive fibers shift opacity as body temperature changes.</li>
                </ul>
                <p>Each upper is cut by hand to respect grain direction. No panel is wasted; offcuts become card holders or key loops gifted with repairs.</p>
            </article>
            <article>
                <h3>Midsole Architecture</h3>
                <ul>
                    <li><strong>Pulse Foam:</strong> nitrogen-infused to rebound energy while remaining featherlight.</li>
                    <li><strong>Lotus Pods:</strong> modular gel capsules inserted along pressure maps unique to each size run.</li>
                    <li><strong>Memory Filaments:</strong> braided carbon threads anchor the arch without stiffening the flex grooves.</li>
                </ul>
                <p>We engineer every midsole to feel like a supportive whisper. Nothing squeaks. Nothing drags. Nothing breaks a dancer’s flow.</p>
            </article>
            <article>
                <h3>Outsole Stories</h3>
                <ul>
                    <li><strong>City Grip:</strong> recycled tires sculpted into wave patterns inspired by Tokyo subway tiles.</li>
                    <li><strong>Dune Flex:</strong> multi-directional lugs shaped after desert wind lines to disperse sand quickly.</li>
                    <li><strong>Studio Silence:</strong> rubber compounds tuned to muffle sound for performers moving across marble.</li>
                </ul>
                <p>We brand the outsole interior with poetic lines. Only you know the verse pressing against the ground, yet it subtly lifts every stride.</p>
            </article>
        </div>
    </div>
</section>

<section class="about-impact container">
    <div class="section-header">
        <h2>Community & Impact</h2>
        <p>Business should feel like a communal studio. Here is how we invest in people, planet, and perspectives.</p>
    </div>
    <div class="impact-grid">
        <article>
            <h3>Loopback Repair Bars</h3>
            <p>Bring any pair—ours or someone else’s—and our cobblers will repair, recondition, or recycle it while narrating the process. Each repair comes with a micro zine outlining the techniques used.</p>
        </article>
        <article>
            <h3>Scholarship for Future Makers</h3>
            <p>We fund apprenticeships for young artisans who want to master pattern engineering, dye work, or supply-chain ethics. They shadow mentors in Vietnam, Italy, and Kenya.</p>
        </article>
        <article>
            <h3>Movement Labs</h3>
            <p>Monthly residencies give choreographers, athletes, and disability advocates access to prototypes. They document how shoes respond to unique bodies, and we implement the feedback within three development cycles.</p>
        </article>
        <article>
            <h3>Digital Transparency Stack</h3>
            <p>Scan any NFC tag in our packaging to see origin videos, cost-of-goods breakdowns, and carbon footprint dashboards. Radical transparency dismantles greenwashing.</p>
        </article>
    </div>
</section>

<section class="about-future">
    <div class="container">
        <div class="future-grid">
            <div>
                <h2>Looking Ahead</h2>
                <p>Our next decade focuses on <strong>adaptive comfort</strong>, <strong>collaborative archives</strong>, and <strong>circular commerce</strong>. We are prototyping dynamic lasts that adjust throughout the day, so shoes adapt to swelling, sprinting, or sudden dance battles. We are building an open-source library of patterns so independent makers can remix our silhouettes legally. We are experimenting with rental tiers for special-occasion footwear, because not every heel needs to live in your wardrobe forever.</p>
                <p>Most importantly, we continue to honor the relationship between maker and wearer. Shoes are intimate objects; they witness celebrations, heartbreaks, boardroom wins, and airport delays. We promise to keep designing with empathy so each pair feels like a confidant.</p>
            </div>
            <div class="future-card">
                <h3>Next quarter highlights</h3>
                <ul>
                    <li>Launch of the Atlas Research Journal documenting foam degradation over a five-year span.</li>
                    <li>Co-lab capsule with ceramic artist Mei Tan, featuring glaze-inspired gradients on leather uppers.</li>
                    <li>Expansion of the Loopback Program to include pick-up points in 24 additional cities.</li>
                    <li>Release of a slow-documentary series following one pair from sketch to first scuff mark.</li>
                </ul>
                <p class="note">Follow our newsroom or subscribe to the atelier log to get early invites.</p>
            </div>
        </div>
    </div>
</section>

<section class="about-contact container">
    <div class="contact-grid">
        <div>
            <h2>Visit the Atelier</h2>
            <p>Our flagship sits in a renovated printing house. Expect the scent of cedar, playlists curated by vinyl collectors, and a repair bar where you can sip tea while artisans revive old favorites.</p>
            <ul class="contact-list">
                <li><strong>Address:</strong> 43-13 Shiohara, Minami-ku, Fukuoka, Japan</li>
                <li><strong>Hours:</strong> Tuesday – Sunday, 10:00–20:00 JST</li>
                <li><strong>Phone:</strong> +81 92 555 8080</li>
                <li><strong>Email:</strong> atelier@V.AShoes.jp</li>
            </ul>
        </div>
        <div>
            <h2>Talk With Us</h2>
            <p>Questions about fit, material care, or storytelling collaborations? Our concierge is staffed by actual makers who can describe every stitch.</p>
            <form class="contact-form">
                <label>
                    <span>Your name</span>
                    <input type="text" placeholder="Enter your name">
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" placeholder="Enter your email">
                </label>
                <label>
                    <span>Topic</span>
                    <select>
                        <option>Custom orders</option>
                        <option>Wholesale & showroom</option>
                        <option>Press & editorial</option>
                        <option>Repair & Loopback</option>
                    </select>
                </label>
                <label>
                    <span>Message</span>
                    <textarea rows="4" placeholder="Share your story"></textarea>
                </label>
                <button type="button">Send message</button>
            </form>
        </div>
    </div>
</section>
')
ON DUPLICATE KEY UPDATE `html_content` = VALUES(`html_content`);

-- Insert default Q&A page content
INSERT INTO `site_contents` (`page_key`, `html_content`) VALUES
('qna',
'<section class="qna-page">
    <div class="container">
        <header class="qna-hero">
            <div class="qna-hero-text">
                <p class="qna-eyebrow">Concierge knowledge base</p>
                <h1>Questions answered with atelier-level care</h1>
                <p class="qna-hero-subcopy">Every policy, promise, and fitting ritual lives in one calm surface so you can glide from curiosity to checkout without friction.</p>
                <div class="qna-chip-row">
                    <span class="qna-chip">24/7 live support</span>
                    <span class="qna-chip">Same-day responses</span>
                    <span class="qna-chip">Global fulfillment</span>
                </div>
            </div>
            <div class="qna-hero-meta">
                <div class="qna-hero-card">
                    <span>Average response</span>
                    <strong>12 min</strong>
                    <p>Dedicated stylists watching the queue.</p>
                </div>
                <div class="qna-hero-card">
                    <span>Resolution rate</span>
                    <strong>98%</strong>
                    <p>Questions closed in the first reply.</p>
                </div>
                <div class="qna-hero-card">
                    <span>Coverage</span>
                    <strong>14 topics</strong>
                    <p>Shipping, returns, sizing, warranty, perks.</p>
                </div>
            </div>
        </header>

        <section class="qna-highlight-grid">
            <article class="qna-highlight-card">
                <div class="qna-highlight-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div>
                    <h3>Shipping Windows</h3>
                    <p>Standard parcels depart in 24 hours, express orders leave within six.</p>
                </div>
            </article>
            <article class="qna-highlight-card">
                <div class="qna-highlight-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div>
                    <h3>Return Ritual</h3>
                    <p>30-day window, prepaid labels, instant credit once scanned.</p>
                </div>
            </article>
            <article class="qna-highlight-card">
                <div class="qna-highlight-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div>
                    <h3>Warranty Studio</h3>
                    <p>Repairs and replacements executed by the same artisans.</p>
                </div>
            </article>
        </section>

        <section class="qna-accordion-section">
            <div class="qna-accordion">
                <div class="qna-accordion-item is-open">
                    <button class="qna-accordion-toggle" type="button">
                        <span>How do shipping tiers work?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Orders over <strong>$50</strong> ship free via standard service (3-5 business days). <em>Express</em> upgrades promise doorstep arrival in <u>1-2 days</u> and unlock priority packing. Tracking links are emailed and texted once the parcel clears quality control.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>What is the return and exchange flow?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Initiate a return within <strong>30 days</strong> and keep the pair unworn with tags. Print the prepaid label or schedule a concierge pickup. Refunds land within 5-7 business days, while exchanges ship the same day the original scan registers.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>How can I confirm my size?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Each product page hosts a size translator, foot tracing guide, and fit notes from stylists. If you still hesitate, tap the live chat to book a video fitting. Free exchanges ensure you can test another size without extra shipping fees.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>Do shoes include a warranty?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>All footwear includes a <strong>6-12 month</strong> craft warranty that covers stitching, eyelets, soles, and hardware. Submit photos through the concierge portal; repairs happen in our partner ateliers or the pair is replaced outright.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>Where can I track my parcel?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Once the box leaves our studio you receive tracking via email and SMS. The dashboard inside <strong>My Account</strong> mirrors the same data plus delivery estimates. For multi-box shipments, each tracking link is grouped in one timeline.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>Do you operate physical stores?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Yes, we run flagships in major cities with on-site fit labs, repair bars, and concierge lounges. Check the <strong>Visit Us</strong> section for addresses, opening hours, and appointment slots.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>Can I split payments?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Yes, choose between <em>Shop Pay Installments</em>, <u>Klarna</u>, or your card issuer’s flexible plan. The checkout page displays the breakdown before you confirm the order, and there are zero hidden fees.</p>
                    </div>
                </div>
                <div class="qna-accordion-item">
                    <button class="qna-accordion-toggle" type="button">
                        <span>How do I speak with a human?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="qna-accordion-content">
                        <p>Live chat, WhatsApp, email, and phone are staffed round the clock. Share your order number or tell us what you are browsing and we route you to the right specialist instantly.</p>
                    </div>
                </div>
            </div>
            <aside class="qna-help-panel">
                <h3>Need concierge help?</h3>
                <p>Slide into a live thread or book a fitting session tailored to your schedule.</p>
                <ul>
                    <li><i class="fas fa-comments"></i> Live chat: 24/7</li>
                    <li><i class="fas fa-phone-volume"></i> Hotline: +81 92 555 8080</li>
                    <li><i class="fas fa-envelope"></i> concierge@V.AShoes.jp</li>
                    <li><i class="fas fa-video"></i> Virtual fittings by appointment</li>
                </ul>
                <button class="btn qna-help-btn" type="button">Start a conversation</button>
            </aside>
        </section>

        <section class="qna-table-section">
            <h2>Policy cheatsheet</h2>
            <table class="qna-quick-table">
                <thead>
                    <tr>
                        <th>Shipping</th>
                        <th>Returns</th>
                        <th>Warranty</th>
                        <th>Perks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Free standard</strong><br><em>3-5 days nationwide</em></td>
                        <td><strong>30 days</strong><br><em>Prepaid label + instant credit</em></td>
                        <td><strong>Up to 12 months</strong><br><em>Craft defects fully covered</em></td>
                        <td><strong>Loyalty tiers</strong><br><em>Priority drops, private repairs</em></td>
                    </tr>
                    <tr>
                        <td><strong>Express</strong><br><em>1-2 day arrival or fee waived</em></td>
                        <td><strong>Exchange free</strong><br><em>Size swaps unlimited</em></td>
                        <td><strong>Loopback</strong><br><em>Refresh + refurbish credits</em></td>
                        <td><strong>Gift wrap</strong><br><em>Complimentary on request</em></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</section>')
ON DUPLICATE KEY UPDATE `html_content` = VALUES(`html_content`);

