<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\PrePopulatedData;

class PrePopulatedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doc_types = [
            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Tender Notice',
                    'description' => 'Provides an overview of the tender opportunity, including key dates and contact information.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Tender Brief',
                    'description' => 'Summary of the tender requirements and objectives, offering an overview of the project or procurement needs.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Technical Specifications',
                    'description' => 'Detailed specifications outlining the technical requirements for the goods or services being procured.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Scope of Work (SoW)',
                    'description' => 'Describes the tasks, deliverables, and expectations for the project, providing clear guidance on requirements.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Bill of Quantities (BoQ)',
                    'description' => 'Lists the quantities and types of materials or services needed, commonly used in construction tenders.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Tender Addendum',
                    'description' => 'An official update or amendment to the original tender documents, often issued in response to supplier queries.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Clarifications',
                    'description' => 'Responses to supplier questions or requests for additional information or explanations.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Tender Terms and Conditions',
                    'description' => 'Legal and contractual terms that will govern the tender and any subsequent contract.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Non-Disclosure Agreement (NDA)',
                    'description' => 'A confidentiality agreement that suppliers may need to sign before accessing sensitive information.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Pricing Schedule',
                    'description' => 'A template or format for suppliers to submit their pricing proposals, ensuring consistency.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Evaluation Criteria',
                    'description' => 'Outlines how bids will be evaluated, including the scoring methodology and key criteria.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Compliance Requirements',
                    'description' => 'List of mandatory requirements that suppliers must meet, such as certifications or licenses.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Project Timeline',
                    'description' => 'A detailed schedule that outlines key milestones and deadlines for the project.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Draft Contract',
                    'description' => 'Preliminary version of the contract, allowing suppliers to review the terms in advance.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Safety and Environmental Requirements',
                    'description' => 'Guidelines and standards for safety, health, and environmental considerations.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Supplier Qualification Criteria',
                    'description' => 'Specifies the qualifications, experience, and capabilities required from suppliers.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Instructions to Bidders',
                    'description' => 'Detailed instructions on bid submission procedures, deadlines, and required documents.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Bid Submission Form',
                    'description' => 'A standardized form that suppliers must complete and submit as part of their tender response.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Project Drawings and Diagrams',
                    'description' => 'Visual representations, such as architectural drawings or technical diagrams.'
                ]
            ],

            [
                'type' => 'document_type',
                'data' => [
                    'label' => 'Site Visit Information',
                    'description' => 'Details about required site visits, including dates, locations, and logistics.'
                ]
            ],
        ];

        foreach($doc_types as $doc_type) {
            PrePopulatedData::create($doc_type);
        }

        $sourcings = [
            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Open Tender',
                    'description' => 'A public tender where any interested supplier can submit a bid. Typically used for large-scale projects.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Closed/Selective Tender',
                    'description' => 'A tender where only selected or pre-qualified suppliers are invited to submit bids.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Framework Agreement',
                    'description' => 'A long-term agreement with one or more suppliers for the supply of goods or services over a specified period, without committing to specific quantities.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Pre-Qualification Tender',
                    'description' => 'A process where suppliers are screened and pre-qualified based on their capabilities before being invited to submit bids.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Competitive Dialogue',
                    'description' => 'A tendering process that allows for dialogue between the buyer and potential suppliers to develop one or more solutions to meet the buyer’s requirements.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Expression of Interest (EOI)',
                    'description' => 'A process used to gauge interest from potential suppliers before issuing a formal tender.',
                    'type' => 'Tender'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Request for Proposal (RFP)',
                    'description' => 'A process where suppliers are invited to submit proposals based on detailed requirements, including technical and financial aspects.',
                    'type' => 'Quotation'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Request for Quotation (RFQ)',
                    'description' => 'A process where suppliers are invited to submit price quotes for specific goods or services, usually for straightforward purchases.',
                    'type' => 'Quotation'
                ]
            ],

            [
                'type' => 'type_of_sourcing',
                'data' => [
                    'label' => 'Request for Information (RFI)',
                    'description' => 'A preliminary process used to gather information from suppliers about their capabilities and offerings before launching a formal tender.',
                    'type' => 'Quotation'
                ]
            ],
        ];

        foreach($sourcings as $sourcing) {
            PrePopulatedData::create($sourcing);
        }

        $evaluation_types = [
            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Lowest Price',
                    'description' => 'Awarded to the bidder with the lowest price meeting all technical requirements.',
                ]
            ],

            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Best Value',
                    'description' => 'Considers both price and quality, balancing cost and performance.',
                ]
            ],

            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Quality-Based',
                    'description' => 'Focuses primarily on the technical proposal and quality, with price as a secondary consideration.',
                ]
            ],

            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Cost-Plus',
                    'description' => 'The bidder is reimbursed for actual costs incurred plus a fixed fee or percentage.',
                ]
            ],

            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Two-Envelope System',
                    'description' => 'Separate technical and financial proposals, evaluated independently.',
                ]
            ],

            [
                'type' => 'evaluation_type',
                'data' => [
                    'label' => 'Weighted Criteria',
                    'description' => 'Uses a scoring system to evaluate bids based on multiple factors like price, quality, and experience.',
                ]
            ],
        ];

        foreach($evaluation_types as $evaluation_type) {
            PrePopulatedData::create($evaluation_type);
        }

        $delivery_terms = [
            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Ex Works',
                    'description' => 'Goods are made available at the supplier’s premises. Buyer bears all costs and risks from there.',
                    'code' => 'EXW'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Free Carrier',
                    'description' => 'Seller delivers the goods to a carrier at a specified location; buyer assumes all responsibility afterward.',
                    'code' => 'FCA'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Free Alongside Ship',
                    'description' => 'Seller delivers goods alongside the ship at the port of shipment; buyer handles loading, shipping, and costs.',
                    'code' => 'FAS'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Free on Board',
                    'description' => 'Seller delivers goods onto the ship; buyer assumes responsibility once goods are on board.',
                    'code' => 'FOB'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Cost and Freight',
                    'description' => 'Seller pays for transportation to the destination port; buyer assumes risk after goods are loaded onto the ship.',
                    'code' => 'CFR'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Cost, Insurance, and Freight',
                    'description' => 'Seller pays for transportation and insurance to the destination port; risk transfers to buyer after shipment.',
                    'code' => 'CIF'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Carriage Paid To',
                    'description' => 'Seller pays for transportation to a specified destination; buyer assumes risk after goods are handed to the carrier.',
                    'code' => 'CPT'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Carriage and Insurance Paid To',
                    'description' => 'Seller pays for transportation and insurance to a specified destination; risk transfers to buyer at carrier handover.',
                    'code' => 'CIP'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Delivered at Terminal',
                    'description' => 'Seller delivers goods to a specified terminal; buyer is responsible for unloading and further transport.',
                    'code' => 'DAT'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Delivered at Place',
                    'description' => 'Seller delivers goods to a specified place; buyer is responsible for unloading and any subsequent costs.',
                    'code' => 'DAP'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Delivered Duty Paid',
                    'description' => 'Seller delivers goods to the buyer’s location, covering all costs including duties; buyer is responsible only after delivery.',
                    'code' => 'DDP'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Ex Quay',
                    'description' => 'Seller delivers goods to the quay (dockside) at the destination port; buyer handles unloading and further transport.',
                    'code' => 'EXQ'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Delivered Duty Unpaid',
                    'description' => 'Seller delivers goods to the buyer’s location; buyer is responsible for paying import duties and taxes.',
                    'code' => 'DDU'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Delivered',
                    'description' => 'Seller is responsible for delivering goods to a specific location; buyer handles unloading and any further steps.',
                    'code' => 'DEL'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Point of Import',
                    'description' => 'Goods are delivered to the point of import; buyer is responsible for duties, taxes, and further transport.',
                    'code' => 'PII'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Point of Export',
                    'description' => 'Goods are delivered to the point of export; buyer is responsible for export clearance, transportation, and further costs.',
                    'code' => 'PEX'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Mixed Mode',
                    'description' => 'Combination of different delivery modes (e.g., air, sea, road) used for transportation; responsibility is shared according to terms agreed upon.',
                    'code' => 'MIX'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Free Store',
                    'description' => 'Goods are delivered to a designated storage facility; seller covers all costs until delivery; buyer handles storage and any subsequent actions.',
                    'code' => 'FST'
                ]
            ],

            [
                'type' => 'delivery_term',
                'data' => [
                    'label' => 'Freight Terminal Pickup',
                    'description' => 'Buyer picks up the goods from a designated freight terminal; seller is responsible for transportation to the terminal.',
                    'code' => 'FTP'
                ]
            ],
        ];

        foreach($delivery_terms as $delivery_term) {
            PrePopulatedData::create($delivery_term);
        }

        $uoms = [
            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Pieces (pcs)',
                    'description' => 'Individual items or units',
                ]
            ],
            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Kilograms (kg)',
                    'description' => 'Weight in kilograms',
                ]
            ],
            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Grams (g)',
                    'description' => 'Weight in grams',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Liters (L)',
                    'description' => 'Volume in liters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Milliliters (mL)',
                    'description' => 'Volume in milliliters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Meters (m)',
                    'description' => 'Length in meters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Centimeters (cm)',
                    'description' => 'Length in centimeters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Millimeters (mm)',
                    'description' => 'Length in millimeters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Square Meters (sq.m)',
                    'description' => 'Area in square meters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Cubic Meters (cu.m)',
                    'description' => 'Volume in cubic meters',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Tons (tons)',
                    'description' => 'Weight in metric tons',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Pounds (lbs)',
                    'description' => 'Weight in pounds',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Ounces (oz)',
                    'description' => 'Weight in ounces',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Gallons (gal)',
                    'description' => 'Volume in gallons',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Barrels (bbl)',
                    'description' => 'Volume in barrels (commonly used for oil)',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Dozens (doz)',
                    'description' => 'Quantity in dozens',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Pairs (prs)',
                    'description' => 'Quantity in pairs',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Boxes (box)',
                    'description' => 'Quantity in boxes',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Pallets (plt)',
                    'description' => 'Quantity in pallets',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Sets (set)',
                    'description' => 'Quantity in sets',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Rolls (roll)',
                    'description' => 'Quantity in rolls (commonly used for materials like fabric)',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Bags (bag)',
                    'description' => 'Quantity in bags (commonly used for bulk goods like cement or grain)',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Packs (pk)',
                    'description' => 'Quantity in packs',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Bundles (bdl)',
                    'description' => 'Quantity in bundles',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Hours (hrs)',
                    'description' => 'Time in hours (commonly used for services)',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Days (days)',
                    'description' => 'Time in days',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Weeks (wks)',
                    'description' => 'Time in weeks',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Months (months)',
                    'description' => 'Time in months',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Years (years)',
                    'description' => 'Time in years',
                ]
            ],

            [
                'type' => 'uom',
                'data' => [
                    'label' => 'Carats (ct)',
                    'description' => 'Weight in carats (commonly used for gemstones)',
                ]
            ],
        ];

        foreach($uoms as $uom) {
            PrePopulatedData::create($uom);
        }

        $payment_terms = [
            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Net 30',
                    'description' => 'Payment for Office Supplies: The government agrees to pay for office supplies within 30 days after receiving the invoice from the supplier.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Net 60',
                    'description' => 'Payment for IT Services: The government agrees to pay for IT services within 60 days after the service has been delivered and the invoice received.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Net 90',
                    'description' => 'Payment for Infrastructure Project: The government agrees to pay for construction materials or services within 90 days after the invoice is received, allowing for extended cash flow management.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Immediate Payment',
                    'description' => 'Purchase of Emergency Supplies: The government makes an immediate payment upon receipt of the invoice, typically used in urgent procurement situations.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Advance Payment',
                    'description' => 'Advance for Construction Project: The government makes an upfront payment of 20% of the total contract value before the start of a construction project. The remaining amount is paid in installments as the project progresses.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Milestone Payments',
                    'description' => 'Payment for Software Development: The payment is divided into multiple installments, with payments made upon the completion of specific milestones, such as 25% upon design completion, 50% after the prototype, and 25% on final delivery.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Letter of Credit (LC)',
                    'description' => 'International Purchase of Machinery: The government arranges for a letter of credit through a bank, ensuring that the supplier will be paid once they fulfill the conditions outlined in the LC, such as shipment and documentation.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Progress Payments',
                    'description' => 'Large Infrastructure Project: Payments are made periodically based on the percentage of work completed, verified through progress reports.',
                ]
            ],

            [
                'type' => 'payment_term',
                'data' => [
                    'label' => 'Cash on Delivery (COD)',
                    'description' => 'Delivery of Small Office Equipment: The government agrees to pay for small office equipment upon delivery, ensuring that the goods are received and inspected before payment is made.',
                ]
            ],
        ];

        foreach($payment_terms as $payment_term) {
            PrePopulatedData::create($payment_term);
        }

        $payment_modes = [
            [
                'type' => 'payment_mode',
                'data' => [
                    'label' => 'Credit Card',
                    'description' => 'Purchase of Office Supplies Online: The government uses a corporate credit card to pay for office supplies purchased from an online vendor.',
                ]
            ],

            [
                'type' => 'payment_mode',
                'data' => [
                    'label' => 'Electronic Funds Transfer (EFT)',
                    'description' => 'Regular Payment for Utility Services: The government uses EFT to make automatic, scheduled payments for recurring services like utilities, ensuring timely and consistent payments.',
                ]
            ],

            [
                'type' => 'payment_mode',
                'data' => [
                    'label' => 'Mobile Payment',
                    'description' => 'Small Purchases or Services: For small, on-the-go transactions, such as paying for quick repair services, the government uses a mobile payment app to transfer funds instantly to the service provider.',
                ]
            ],

            [
                'type' => 'payment_mode',
                'data' => [
                    'label' => 'Purchase Order Financing',
                    'description' => 'Long-Term Contracts: The government agrees to use purchase order financing, where a financial institution pays the supplier upfront based on the issued purchase order, and the government pays the financier later.',
                ]
            ],
        ];

        foreach($payment_modes as $payment_mode) {
            PrePopulatedData::create($payment_mode);
        }

        $currencies = [
            [
                'type' => 'currency',
                'data' => [
                    'label' => 'USD',
                    'description' => 'United state dollar',
                ]
            ],
        ];

        foreach($currencies as $currency) {
            PrePopulatedData::create($currency);
        }

        $submission_modes = [
            [
                'type' => 'submission_mode',
                'data' => [
                    'label' => 'Electronically',
                    'description' => 'Electronically',
                ]
            ],

            [
                'type' => 'submission_mode',
                'data' => [
                    'label' => 'Physically',
                    'description' => 'Physically',
                ]
            ],
        ];

        foreach($submission_modes as $submission_mode) {
            PrePopulatedData::create($submission_mode);
        }

        PrePopulatedData::create(
            [
                'type' => 'expected_delivery_date',
                'data' => [
                    'label' => '30 days after PO',
                    'description' => '30 days after PO',
                ]
            ],
        );
    }
}