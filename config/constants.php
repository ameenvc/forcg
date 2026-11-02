<?php

return [
    'vendor' => [
        'start_year' => 1901,
    ],
    'app_url' => env('APP_URL'),
    'risk_management' => [
        'mitigation' => [
            'priorities' => [
                'high' => 'High',
                'medium' => 'Medium',
                'low' => 'Low',
            ],
            'statuses' => [
                'not_started' => 'Not Started',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
            ],
            'resources' => [
                'budget' => 'Budget',
                'personnel' => 'Personnel',
                'equipment' => 'Equipment',
            ],
            'progresses' => [
                'not_started' => 'Not Started',
                'in_progress_0' => 'In Progress (0%)',
                'in_progress_25' => 'In Progress (25%)',
                'in_progress_50' => 'In Progress (50%)',
                'in_progress_75' => 'In Progress (75%)',
                'completed' => 'Completed',
            ],
            'statuses_mapping_progresses' => [
                'not_started' => 'not_started',
                'in_progress_0' => 'in_progress',
                'in_progress_25' => 'in_progress',
                'in_progress_50' => 'in_progress',
                'in_progress_75' => 'in_progress',
                'completed' => 'completed',
            ],
            'progress_percent' => [
                'not_started' => 0,
                'in_progress_0' => 0,
                'in_progress_25' => 25,
                'in_progress_50' => 50,
                'in_progress_75' => 75,
                'completed' => 100,
            ],
            'closed' => 'Closed'
        ],
        'category' => [
           'hr' => [
                'value' => 'Human Resource',
                'details' => [
                    [
                        'name' => 'Employee Turnover and Attrition',
                        'description' => 'Employee turnover and attrition refer to the rate at which employees leave the organization voluntarily or involuntarily.',
                        'driver' => 'Inadequate compensation and benefits.
                                    Lack of opportunities for career advancement.
                                    Poor work-life balance.
                                    Dissatisfaction with management or company culture.
                                    Market competition for skilled employees.
                                    Ineffective employee recognition and rewards programs.
                                    Limited opportunities for skill development and training.
                                    Organizational instability or uncertainty.'
                    ],
                    [
                        'name' => 'Recruitment and Retention Challenges',
                        'description' => 'Difficulties in attracting qualified candidates and retaining talented employees.',
                        'driver' => 'Limited pool of qualified candidates.
                                    Reputation management issues.
                                    Ineffective recruitment strategies.
                                    Lack of employer branding.
                                    High turnover rates.
                                    Competitor poaching.
                                    Changing workforce demographics.
                                    Insufficient retention incentives or benefits.'
                    ],
                    [
                        'name' => 'Employment Law Compliance',
                        'description' => 'Adherence to laws and regulations governing employment practices.',
                        'driver' => 'Evolving legal landscape.
                                    Lack of understanding or awareness of employment laws.
                                    Non-compliance with wage and hour regulations.
                                    Discrimination in hiring or promotion practices.
                                    Inadequate record-keeping.
                                    Misclassification of employees.
                                    Failure to accommodate disabilities or religious practices.
                                    Unfair labor practices.'
                    ],
                    [
                        'name' => 'Discrimination and Harassment Claims',
                        'description' => 'Allegations of discrimination or harassment in the workplace.',
                        'driver' => 'Hostile work environment.
                                    Ineffective diversity and inclusion initiatives.
                                    Poorly enforced anti-discrimination policies.
                                    Biases in hiring, promotion, or compensation.
                                    Inadequate training on harassment prevention.
                                    Retaliation against whistleblowers.
                                    Cultural insensitivity.
                                    Power imbalances.'
                    ],
                    [
                        'name' => 'Data Security and Privacy Risks',
                        'description' => 'Risks associated with the protection of sensitive company and employee data.',
                        'driver' => 'Cybersecurity threats such as hacking, malware, and phishing attacks.
                                    Inadequate data protection measures.
                                    Insider threats and data breaches.
                                    Lack of employee awareness on data security protocols.
                                    Compliance requirements with data protection regulations (e.g., GDPR, CCPA).
                                    Increasing reliance on digital platforms and cloud services.
                                    Third-party vendor risks.
                                    Data governance challenges and complexities.'
                    ],
                    [
                        'name' => 'Succession Planning Gaps',
                        'description' => 'Insufficient preparation for filling key roles in the organization.',
                        'driver' => 'Aging workforce.
                                    Lack of talent pipeline.
                                    Failure to identify and develop future leaders.
                                    Unexpected departures of key personnel.
                                    Inadequate knowledge transfer.
                                    Insufficient investment in leadership development programs.
                                    Rapid changes in technology or industry dynamics.
                                    Succession planning being sidelined due to immediate operational concerns.'
                    ],
                    [
                        'name' => 'Training and Development Needs',
                        'description' => 'Gaps in employee skills and competencies.',
                        'driver' => 'Rapid technological advancements.
                                    Lack of training resources.
                                    Ineffective training programs.
                                    Failure to address changing job requirements.
                                    Insufficient focus on continuous learning.
                                    Budget constraints.
                                    Resistance to change.
                                    Mismatch between employee skills and organizational needs.'
                    ],
                    [
                        'name' => 'Employee Morale and Engagement',
                        'description' => 'Employee satisfaction, motivation, and commitment to the organization.',
                        'driver' => 'Poor leadership or management practices.
                                    Lack of recognition and appreciation.
                                    Limited opportunities for growth and advancement.
                                    Communication breakdowns.
                                    High workload or job dissatisfaction.
                                    Unclear expectations or goals.
                                    Workplace conflicts.
                                    Disconnect between company values and actions.'
                    ]
                ]
            ],
           'sam' => [
               'value' => 'Sales and Marketing',
               'details' => [
                   [
                       'name' => 'Fluctuating Market Demand',
                       'description' => 'Changes in consumer preferences, economic conditions, or competitive dynamics can lead to fluctuations in demand for products or services, impacting sales revenue and market share.',
                       'driver' => 'Economic downturns or recessions affecting consumer confidence and purchasing power.
                                    Shifts in demographic trends, consumer behavior, or lifestyle preferences influencing product demand.
                                    Intense competition and pricing pressures in the market, leading to aggressive marketing tactics and discounting strategies.
                                    Disruptions caused by technological innovations, industry trends, or regulatory changes affecting product offerings or market positioning.'
                   ],
                   [
                       'name' => 'Brand Reputation Risks',
                       'description' => "Negative publicity, customer complaints, or product/service failures can damage the organization's brand reputation, trust, and credibility in the market, impacting customer loyalty and future sales.",
                       'driver' => "Product recalls, quality issues, or safety concerns affecting consumer perceptions of product reliability and trustworthiness.
                                    Social media backlash, viral negative publicity, or influencer-driven controversies amplifying brand reputation risks and damaging brand equity.
                                    Unethical business practices, corporate scandals, or legal disputes tarnishing the organization's image and undermining stakeholder trust.
                                    Failure to meet customer expectations, deliver on brand promises, or address customer feedback and complaints effectively."
                   ],
                   [
                       'name' => 'Competitive Pressure and Market Share Loss',
                       'description' => 'Intense competition in the market and loss of market share to competitors can erode sales revenue, diminish brand value, and impede business growth.',
                       'driver' => "Entry of new competitors into the market offering similar products or services at lower prices.
                                    Aggressive marketing and promotional campaigns by competitors targeting the organization's customer base.
                                    Product innovation or differentiation by competitors leading to shifts in consumer preferences and loyalty.
                                    Strategic alliances or partnerships formed by competitors to gain competitive advantages in the market."
                   ],
                   [
                       'name' => 'Sales Forecasting Inaccuracies',
                       'description' => 'Inaccurate sales forecasts can lead to inventory imbalances, production inefficiencies, and missed revenue targets, impacting profitability and operational performance.',
                       'driver' => "Lack of historical sales data or market intelligence for accurate forecasting.
                                    Fluctuations in customer demand patterns or buying behavior, making predictions challenging.
                                    Inadequate communication and collaboration between sales teams, marketing departments, and supply chain functions.
                                    External factors such as seasonality, economic volatility, or geopolitical events affecting sales projections."
                   ],
                   [
                       'name' => 'Product or Service Launch Failures',
                       'description' => "Failures or setbacks in launching new products or services can result in wasted investments, missed market opportunities, and damage to the organization's reputation and market position.",
                       'driver' => "Inadequate market research and validation of customer needs and preferences before product development.
                                    Poor product design, quality issues, or functionality shortcomings discovered after launch.
                                    Insufficient marketing and promotional support for new product launches, resulting in low customer awareness and adoption.
                                    Competitive pressures or market conditions shifting unfavorably post-launch, impacting sales performance and market penetration."
                   ],
                   [
                       'name' => 'Customer Relationship',
                       'description' => "Ineffective customer relationship management practices can lead to poor customer satisfaction, reduced customer retention, and loss of business opportunities.",
                       'driver' => "Inadequate customer segmentation and targeting strategies, resulting in irrelevant or inconsistent communication with customers.
                                    Lack of personalized or tailored marketing efforts to address individual customer needs and preferences.
                                    Suboptimal customer service experiences, including slow response times, unresolved complaints, or poor post-sale support.
                                    Competitive pressures leading to customer churn, as rivals offer better value propositions or superior customer experiences."
                   ],
                   [
                       'name' => 'Pricing Strategy',
                       'description' => "Poor pricing decisions or strategies can result in lost revenue, price wars with competitors, and eroded profit margins, impacting financial performance and market positioning.",
                       'driver' => "Pricing decisions based on cost-plus or arbitrary markups rather than market-driven pricing strategies.
                                    Lack of pricing intelligence or competitive analysis, leading to underpricing or overpricing products/services.
                                    Inconsistent pricing across different sales channels or customer segments, causing confusion or dissatisfaction."
                   ]
               ]
           ],
            'sc' => [
                'value' => 'Supply chain',
                'details' => [
                    [
                        'name' => 'Supply Chain Disruption',
                        'description' => "Interruptions or disturbances in the flow of goods, services, or information within the supply chain.",
                        'driver' => "Natural disasters (e.g., earthquakes, hurricanes, floods).
                                    Political instability and geopolitical conflicts.
                                    Global pandemics or health crises.
                                    Supplier bankruptcies or insolvencies.
                                    Transportation disruptions (e.g., port closures, strikes).
                                    Quality issues with suppliers.
                                    Changes in government regulations or trade policies.
                                    Economic downturns or recessions."
                    ],
                    [
                        'name' => 'Supplier Reliability',
                        'description' => "Dependability and consistency of suppliers in meeting contractual obligations.",
                        'driver' => "Lack of alternative suppliers.
                                    Geographical concentration of suppliers.
                                    Poor supplier performance or quality control.
                                    Supplier capacity constraints.
                                    Financial instability of suppliers.
                                    Ethical or labor violations by suppliers.
                                    Inadequate communication and collaboration with suppliers.
                                    Dependency on single-source suppliers."
                    ],
                    [
                        'name' => 'Inventory Management',
                        'description' => "Difficulties in maintaining optimal inventory levels to meet demand while minimizing costs.",
                        'driver' => "Forecasting errors.
                                    Seasonal demand fluctuations.
                                    Lead time variability.
                                    Inaccurate demand forecasting.
                                    Inefficient inventory management systems.
                                    Bullwhip effect in the supply chain.
                                    Limited visibility across the supply chain.
                                    Storage and warehousing constraints."
                    ],
                    [
                        'name' => 'Quality Control and Product Integrity',
                        'description' => "Risks related to ensuring the quality and integrity of products throughout the supply chain.",
                        'driver' => "Inadequate supplier quality management.
                                    Counterfeit or substandard products.
                                    Product recalls or safety concerns.
                                    Regulatory non-compliance.
                                    Lack of visibility into supplier processes.
                                    Quality control failures at manufacturing facilities.
                                    Poor handling practices during transportation.
                                    Environmental factors affecting product quality."
                    ],
                    [
                        'name' => 'Supplier Risk',
                        'description' => "This risk encompasses the vulnerability associated with relying on external suppliers for crucial goods or services.",
                        'driver' => "Dependency on single-source suppliers increases exposure to disruptions.
                                    Supplier financial instability or bankruptcy can halt the supply chain.
                                    Quality issues with supplier products or services can lead to production delays or customer dissatisfaction.
                                    Geopolitical factors affecting supplier locations may introduce political or regulatory risks.
                                    Ethical or labor violations by suppliers can damage the organization's reputation and lead to supply chain disruptions."
                    ],
                    [
                        'name' => 'Demand Volatility',
                        'description' => "The unpredictability in customer demand patterns, which can create challenges in production planning and inventory management.",
                        'driver' => "Fluctuations in customer demand due to seasonality or market dynamics.
                                    Rapid changes in consumer preferences or trends.
                                    Market shifts or disruptions caused by external factors.
                                    Introducing new products or innovations can lead to uncertain demand patterns."
                    ],
                    [
                        'name' => 'Supply Chain Disruptions',
                        'description' => "The interruption or disturbance in the flow of goods, services, or information within the supply chain.",
                        'driver' => "Natural disasters such as earthquakes, floods, or hurricanes can disrupt manufacturing facilities or transportation networks.
                                    Pandemics or health crises, like COVID-19, can lead to workforce shortages and logistical challenges.
                                    Political instability, conflicts, or trade disputes can result in supply chain disruptions.
                                    Transportation disruptions like port closures or strikes can delay shipments.
                                    Cybersecurity threats such as hacking or ransomware attacks can disrupt operations or compromise sensitive data."
                    ],
                    [
                        'name' => 'Geopolitical Risks',
                        'description' => "Risks associated with political instability, conflicts, or changes in government policies that can impact supply chain operations.",
                        'driver' => "Trade disputes and tariffs can disrupt global supply chains and increase costs.
                                    Political instability or regime changes can lead to uncertainty and unrest in certain regions.
                                    Regional conflicts or wars can disrupt transportation routes or lead to supply chain disruptions.
                                    Changes in government policies affecting trade, such as export controls or import tariffs, can impact supply chain operations.
                                    Economic sanctions imposed on certain countries can restrict trade and affect the availability of goods or services."
                    ]
                ]
            ],
            'lg' => [
                'value' => 'Logistics',
                'details' => [
                    [
                        'name' => 'Transportation Disruptions',
                        'description' => "These disruptions involve interruptions in the movement of goods between suppliers, manufacturers, warehouses, and customers.",
                        'driver' => "Inclement weather conditions such as storms, hurricanes, or heavy snowfall.
                                    Road accidents or infrastructure failures leading to road closures.
                                    Port closures or congestion affecting maritime transportation.
                                    Airspace closures or flight cancellations impacting air freight.
                                    Railway disruptions due to maintenance work, accidents, or strikes."
                    ],
                    [
                        'name' => 'Capacity Constraints',
                        'description' => "This risk involves limitations in available transportation capacity to meet demand, leading to delays or increased costs.",
                        'driver' => "High seasonal demand overwhelming available transportation resources.
                                    Limited availability of trucks, vessels, or aircraft.
                                    Sudden spikes in demand exceeding transportation providers' capacity.
                                    Resource shortages due to unexpected events or disruptions."
                    ],
                    [
                        'name' => 'Fuel Price Volatility',
                        'description' => "Fluctuations in fuel prices can significantly impact transportation costs, affecting logistics operations.",
                        'driver' => "Instability in global oil markets leading to fluctuations in fuel prices.
                                    Geopolitical tensions affecting oil production or distribution.
                                    Changes in government policies or regulations impacting fuel taxes or subsidies.
                                    Environmental factors such as natural disasters disrupting oil production or refining."
                    ],
                    [
                        'name' => 'Route Optimization Challenges',
                        'description' => "Difficulties in optimizing transportation routes to minimize costs and delivery times while considering various constraints.",
                        'driver' => "Inefficient route planning leading to longer transit times and higher fuel consumption.
                                    Limited visibility into real-time traffic conditions or road closures.
                                    Complex logistics networks with multiple stops and destinations.
                                    Regulatory restrictions or tolls affecting route choices."
                    ],
                    [
                        'name' => 'Last-Mile Delivery Issues',
                        'description' => "Challenges related to the final leg of the delivery process from distribution centers to end customers.",
                        'driver' => "Urban congestion and traffic delays in densely populated areas.
                                    Difficulty accessing remote or rural locations.
                                    Customer availability constraints for receiving deliveries.
                                    Theft or damage to packages during last-mile delivery.
                                    High costs associated with last-mile delivery operations."
                    ],
                    [
                        'name' => 'Inventory In-Transit Risks',
                        'description' => "Risks associated with inventory being transported between different locations, including loss, theft, or damage.",
                        'driver' => "Inadequate packaging leading to product damage during transit.
                                    Theft or pilferage of goods during transportation.
                                    Accidents or mishandling resulting in inventory loss.
                                    Inaccurate tracking or visibility into inventory in transit."
                    ],
                    [
                        'name' => 'Customs and Compliance Delays',
                        'description' => "Risks associated with inventory being transported between different locations, including loss, theft, or damage.",
                        'driver' => "Incomplete or inaccurate documentation causing delays in customs clearance.
                                    Changes in import/export regulations or trade agreements affecting customs procedures.
                                    Compliance issues leading to goods being held at customs for inspection.
                                    Cross-border disputes or conflicts impacting trade flows."
                    ],
                    [
                        'name' => 'Supplier Reliability in Transportation',
                        'description' => "Reliance on transportation providers for timely and reliable delivery of goods, with the risk of disruptions due to provider issues.",
                        'driver' => "Carrier capacity constraints or shortages affecting availability.
                                    Carrier financial instability leading to service interruptions.
                                    Poor performance or reliability of transportation providers.
                                    Lack of backup options or alternative transportation providers."
                    ],
                    [
                        'name' => 'Technology Integration Risks',
                        'description' => "Risks associated with the integration of technology systems for logistics operations, including potential disruptions or failures.",
                        'driver' => "Incompatibility between different technology systems or platforms.
                                    IT system failures or downtime impacting logistics operations.
                                    Data security breaches compromising sensitive logistics information.
                                    Lack of robust backup systems or contingency plans for technology failures."
                    ],
                    [
                        'name' => 'Environmental and Sustainability Risks',
                        'description' => "Risks associated with environmental impacts and sustainability concerns in logistics operations.",
                        'driver' => "Regulatory changes or emissions standards affecting transportation modes.
                                    Increasing pressure to reduce carbon emissions and adopt sustainable practices.
                                    Disruptions caused by extreme weather events or climate change.
                                    Reputation damage due to environmental incidents or non-compliance with sustainability standards.
                                    Rising costs associated with environmental regulations and sustainability initiatives."
                   ]
               ]
           ],
           'prd' => [
               'value' => 'Production',
               'details' => [
                   [
                       'name' => 'Equipment Failure',
                       'description' => "This risk involves the breakdown or malfunction of production machinery or equipment, leading to production delays, downtime, and increased maintenance costs.",
                       'driver' => "Aging equipment prone to wear and tear.
                                    Inadequate maintenance schedules or practices.
                                    Heavy usage exceeding equipment capacity.
                                    Lack of spare parts or backup systems.
                                    Human error during operation or maintenance."
                   ],
                   [
                       'name' => 'Quality Control Issues',
                       'description' => "Risks associated with defects or inconsistencies in the quality of produced goods, leading to rework, scrap, or customer dissatisfaction.",
                       'driver' => "Inadequate quality control processes or standards.
                                    Equipment malfunctions affecting product quality.
                                    Variability in raw materials or components.
                                    Human error during production processes.
                                    Lack of employee training on quality standards and procedures."
                   ],
                   [
                       'name' => 'Production Process Bottlenecks',
                       'description' => "Constraints or limitations within production processes that restrict throughput and efficiency, causing delays and inefficiencies.",
                       'driver' => "Inefficient layout or organization of production facilities.
                                    Over-reliance on specific equipment or workstations.
                                    Imbalance in production line capacities.
                                    Lack of flexibility to adapt to changing demand patterns.
                                    Poor workflow coordination between different production stages."
                   ],
                   [
                       'name' => 'Workforce Shortages or Skills Gaps',
                       'description' => "Challenges related to the availability of skilled labor or workforce shortages, affecting production capacity and efficiency.",
                       'driver' => "Aging workforce and retirement of skilled workers.
                                    Difficulty attracting and retaining qualified talent.
                                    Skills gaps due to inadequate training or education programs.
                                    Labor market fluctuations and competition for skilled workers.
                                    Changes in immigration policies impacting workforce demographics."
                   ],
                   [
                       'name' => 'Energy Supply Interruptions',
                       'description' => "Risks associated with disruptions in energy supply (e.g., electricity, gas) needed for production processes, resulting in downtime and reduced output.",
                       'driver' => "Power outages or blackouts caused by grid failures or infrastructure issues.
                                    Natural disasters affecting energy infrastructure.
                                    Energy price volatility impacting operational costs.
                                    Reliance on non-renewable energy sources susceptible to supply disruptions.
                                    Inadequate backup systems or alternative energy sources."
                   ],
                   [
                       'name' => 'Technology and Automation Risks',
                       'description' => "Risks associated with the adoption and implementation of new technologies and automation in production processes.",
                       'driver' => "Integration challenges with existing systems and equipment.
                                    Technical failures or malfunctions in automated systems.
                                    Cybersecurity threats targeting production systems.
                                    Disruption of workflows during technology upgrades or installations.
                                    Skills gaps in operating and maintaining automated equipment."
                   ],
                   [
                       'name' => 'Production Efficiency Challenges',
                       'description' => "Risks associated with inefficiencies in production processes, leading to higher costs, longer lead times, and reduced competitiveness.",
                       'driver' => "Inefficient layout or organization of production facilities.
                                    Equipment downtime or maintenance issues.
                                    Poor workforce scheduling or resource allocation.
                                    Lack of standardized processes or best practices.
                                    Bottlenecks in production workflows."
                   ],
                   [
                       'name' => 'Yield Loss or Variability',
                       'description' => "Risks related to variations in product yield or loss during production, impacting profitability and resource utilization.",
                       'driver' => "Variability in raw material quality or composition.
                                    Inadequate process control leading to inconsistent product quality.
                                    Equipment malfunctions or calibration issues affecting yield.
                                    Human error in production operations.
                                    Lack of real-time monitoring and feedback mechanisms."
                   ],
                   [
                       'name' => 'Raw Material Supply Risks',
                       'description' => "Risks associated with disruptions or constraints in the supply of raw materials needed for production processes.",
                       'driver' => "Dependency on single-source suppliers for critical raw materials.
                                    Supply chain disruptions affecting raw material availability.
                                    Quality issues or variations in raw material specifications.
                                    Geopolitical factors impacting the stability of raw material supply regions.
                                    Price volatility affecting raw material procurement costs."
                   ],
                   [
                       'name' => 'Productivity Losses',
                       'description' => "Risks stemming from reduced productivity levels in production operations, impacting output and operational efficiency.",
                       'driver' => "Workforce shortages or absenteeism affecting production capacity.
                                    Inadequate training or skills gaps among production personnel.
                                    Equipment breakdowns or maintenance activities causing downtime.
                                    Inefficient use of resources or time.
                                    Lack of motivation or engagement among production teams."
                   ],
                   [
                       'name' => 'Quality Control and Rework',
                       'description' => "Risks related to the need for rework or quality control measures during production processes, leading to increased costs and delays.",
                       'driver' => "Variability in product quality requiring additional inspection or rework.
                                    Non-conformities detected during quality control processes.
                                    Inadequate process control leading to defects or errors.
                                    Lack of employee training on quality standards and procedures.
                                    Supplier quality issues affecting incoming materials."
                   ],
                   [
                       'name' => 'Capacity Constraints',
                       'description' => "Limitations in available production capacity to meet demand, resulting in production bottlenecks and inability to fulfill orders.",
                       'driver' => "Insufficient production equipment or facilities to meet demand.
                                    Unexpected spikes in demand exceeding production capacity.
                                    Long lead times for expanding production capabilities.
                                    Inflexible production processes or workflows.
                                    Lack of contingency plans for capacity constraints."
                   ]
               ]
           ],
           'op' => [
               'value' => 'Operations',
               'details' => [
                   [
                       'name' => 'Operational Efficiency Challenges',
                       'description' => "Risks associated with inefficiencies in operational processes, leading to higher costs, longer lead times, and reduced productivity.",
                       'driver' => "Inefficient workflow design or organization.
                                    Lack of standardized procedures or best practices.
                                    Equipment breakdowns or maintenance issues.
                                    Bottlenecks in operational workflows.
                                    Insufficient workforce training or skills gaps."
                   ],
                   [
                       'name' => 'Technology Integration Risks',
                       'description' => "Risks associated with integrating technology systems and processes within operations.",
                       'driver' => "Integration challenges between different technology systems.
                                    Technical failures or malfunctions in automated systems.
                                    Cybersecurity threats targeting operational technology.
                                    Disruption of workflows during technology upgrades.
                                    Skills gaps in operating and maintaining technology systems."
                   ],
                   [
                       'name' => 'Resource Shortages',
                       'description' => "Risks associated with shortages or constraints in critical resources required for operations, such as labor, materials, or energy.",
                       'driver' => "Labor shortages or workforce constraints.
                                    Supply chain disruptions impacting material availability.
                                    Energy supply interruptions affecting production.
                                    Price volatility for essential resources.
                                    Regulatory changes affecting resource availability or usage."
                   ],
                   [
                       'name' => 'Process Interruptions or Downtime',
                       'description' => "Risks related to interruptions or downtime in operational processes, leading to delays in production or service delivery.",
                       'driver' => "Equipment breakdowns or failures.
                                    Software glitches or system crashes.
                                    Power outages or infrastructure failures.
                                    Human error during operational tasks.
                                    Environmental factors such as natural disasters or extreme weather events."
                   ],
                   [
                       'name' => 'Supply Chain Disruptions',
                       'description' => "Interruptions in the flow of goods, services, or information within the supply chain, impacting the ability to meet production schedules or fulfill customer orders.",
                       'driver' => "Supplier bankruptcies or insolvencies affecting the supply of critical materials or components.
                                    Transportation disruptions such as port closures or strikes delaying inbound shipments.
                                    Quality issues with incoming materials impacting production processes.
                                    Geopolitical factors affecting the stability of supplier regions.
                                    Sudden changes in demand leading to supply shortages."
                   ],
                   [
                       'name' => 'Capacity Constraints',
                       'description' => "Limitations in available production capacity, facilities, or resources to meet demand, resulting in production bottlenecks and delays.",
                       'driver' => "Insufficient production equipment or facilities to meet demand fluctuations.
                                    Inflexible production processes unable to adapt to changing requirements.
                                    Labor shortages or skill gaps impacting operational efficiency.
                                    Long lead times for scaling up production capabilities.
                                    Lack of contingency plans for unexpected spikes in demand."
                   ],
                   [
                       'name' => 'Process and Operational Efficiency',
                       'description' => "Risks related to inefficiencies or suboptimal performance in operational processes, impacting productivity, cost-effectiveness, and customer satisfaction.",
                       'driver' => "Inefficient workflow design or organizational structure leading to delays or bottlenecks.
                                    Substandard equipment or technology hindering operational performance.
                                    Lack of continuous improvement initiatives to streamline processes and reduce waste.
                                    Poor communication and coordination among operational teams.
                                    Failure to leverage data analytics and performance metrics for process optimization."
                   ],
                   [
                       'name' => 'Business Continuity and Disaster Recovery',
                       'description' => "Risks associated with disruptions to business operations due to unforeseen events such as natural disasters, cyberattacks, or infrastructure failures.",
                       'driver' => "Natural disasters such as earthquakes, floods, or wildfires impacting operational facilities.
                                    Cybersecurity breaches compromising critical operational systems and data.
                                    Infrastructure failures such as power outages or equipment malfunctions disrupting operations.
                                    Lack of robust contingency plans and disaster recovery procedures.
                                    Insufficient insurance coverage or financial reserves to mitigate the impact of business disruptions."
                   ],
                   [
                       'name' => 'Customer Service Failures',
                       'description' => "Risks related to failures in meeting customer expectations or delivering satisfactory products or services.",
                       'driver' => "Inadequate customer support or communication channels.
                                    Quality issues with products or services.
                                    Delays in order processing or delivery.
                                    Insufficient inventory levels leading to stockouts.
                                    Poorly trained or unmotivated customer service staff."
                   ]
               ]
           ],
           'qua' => [
               'value' => 'Quality',
               'details' => [
                   [
                       'name' => 'Product Defects and Non-Conformities',
                       'description' => "Risks associated with the occurrence of defects or deviations from quality standards in products or services.",
                       'driver' => "Inadequate quality control measures during manufacturing processes.
                                    Variability in raw materials or components affecting product consistency.
                                    Equipment malfunctions leading to production errors or defects.
                                    Human error in production operations or quality inspections.
                                    Lack of employee training on quality standards and procedures."
                   ],
                   [
                       'name' => 'Compliance with Quality Standards and Regulations',
                       'description' => "Risks related to non-compliance with industry-specific quality standards, regulatory requirements, and customer expectations.",
                       'driver' => "Evolving regulatory landscape and compliance requirements.
                                    Failure to meet industry-specific quality certifications (e.g., ISO 9001).
                                    Non-compliance with product safety or labeling regulations.
                                    Quality audits or inspections revealing deficiencies or gaps.
                                    Customer complaints or feedback indicating quality issues."
                   ],
                   [
                       'name' => 'Supplier Quality Risks',
                       'description' => "Risks stemming from the quality performance of suppliers and subcontractors providing materials, components, or services.",
                       'driver' => "Quality issues with incoming materials or components affecting product integrity.
                                    Reliance on single-source suppliers increasing vulnerability to supply disruptions.
                                    Lack of transparency or visibility into supplier quality management practices.
                                    Supplier capacity constraints impacting timely delivery of quality products.
                                    Ethical or compliance violations by suppliers affecting product quality or reputation."
                   ],
                   [
                       'name' => 'Product Recalls and Warranty Claims',
                       'description' => "Risks associated with the need to recall products from the market or address warranty claims due to quality-related issues.",
                       'driver' => "Identification of safety hazards or defects in products post-production.
                                    Customer complaints or adverse events triggering product recalls.
                                    Warranty claims resulting from product failures or defects.
                                    Legal and financial liabilities associated with product recalls or warranty obligations.
                                    Reputational damage due to publicized quality incidents or recalls."
                   ],
                   [
                       'name' => 'Quality Control Processes and Procedures',
                       'description' => "Risks related to deficiencies or gaps in quality control processes and procedures, leading to substandard products or services.",
                       'driver' => "Inadequate documentation of quality control measures and standards.
                                    Lack of clear roles and responsibilities for quality management within the organization.
                                    Insufficient resources or investment in quality control initiatives.
                                    Failure to implement corrective and preventive actions in response to quality issues.
                                    Resistance to change or improvement in quality management practices."
                   ],
                   [
                       'name' => 'Customer Satisfaction and Perception',
                       'description' => "Risks associated with customer dissatisfaction due to perceived quality shortcomings, impacting brand reputation and market competitiveness.",
                       'driver' => "Poor product performance or reliability leading to customer complaints or returns.
                                    Negative reviews or feedback on product quality from customers.
                                    Loss of market share or competitive advantage due to quality-related issues.
                                    Inability to meet customer expectations regarding product quality or service levels.
                                    Reputational damage resulting from publicized quality incidents or scandals."
                   ],
                   [
                       'name' => 'Continuous Improvement and Innovation',
                       'description' => "Risks related to the organization's ability to drive continuous improvement and innovation in quality management processes and products.",
                       'driver' => "Resistance to change or complacency within the organization hindering quality improvement efforts.
                                    Lack of a systematic approach to identifying and implementing quality improvement initiatives.
                                    Inadequate investment in research and development to drive product innovation.
                                    Failure to leverage emerging technologies or best practices for quality management.
                                    Competitor advancements or market disruptions affecting the organization's ability to innovate."
                   ]
               ]
           ],
           'ut' => [
               'value' => 'Utility',
               'details' => [
                   [
                       'name' => 'Fuel Supply Interruptions',
                       'description' => "Risks associated with disruptions in the supply of fuel (such as coal, natural gas, or nuclear fuel) required for power generation, impacting operational continuity and energy production.",
                       'driver' => "Supply chain disruptions affecting the transportation or delivery of fuel to power plants.
                                    Price volatility and availability challenges for fuel sources.
                                    Geopolitical tensions impacting the stability of fuel supply regions.
                                    Regulatory changes affecting fuel procurement practices or emissions standards.
                                    Dependence on imported fuel sources susceptible to trade restrictions or embargoes."
                   ],
                   [
                       'name' => 'Emissions Control and Compliance',
                       'description' => "Risks related to meeting emissions control regulations and compliance requirements for air pollutants generated during power generation, including greenhouse gases, sulfur dioxide, nitrogen oxides, and particulate matter.",
                       'driver' => "Stringent emissions standards and regulations imposed by environmental agencies.
                                    Technological challenges in retrofitting existing power plants with emissions control equipment.
                                    Operational constraints on emissions control systems impacting plant efficiency.
                                    Monitoring and reporting requirements for emissions data and compliance documentation.
                                    Potential penalties or fines for non-compliance with emissions regulations."
                   ],
                   [
                       'name' => 'Water Management Risks',
                       'description' => "Risks associated with water usage, treatment, and discharge at power plants, including water scarcity, contamination, and regulatory compliance for cooling water discharge and wastewater effluent.",
                       'driver' => "Competition for water resources with other sectors such as agriculture and municipal supply.
                                    Regulatory constraints on water abstraction, discharge permits, and effluent quality standards.
                                    Environmental impacts of withdrawing and discharging large volumes of water for cooling purposes.
                                    Contamination of water sources from coal ash disposal or chemical spills.
                                    Technologies and investments required for water conservation, recycling, and treatment."
                   ],
                   [
                       'name' => 'Outage Management and Response',
                       'description' => "Risks related to unplanned outages, scheduled maintenance, and response procedures for restoring power plant operations in the event of equipment failures, grid disturbances, or extreme weather events.",
                       'driver' => "Aging infrastructure and equipment susceptible to breakdowns and failures.
                                    Inadequate planning and scheduling for maintenance activities and equipment overhauls.
                                    Lack of redundancy and backup systems for critical components and subsystems.
                                    Coordination challenges with grid operators and transmission/distribution utilities during grid disturbances.
                                    Availability of spare parts, resources, and skilled personnel for outage restoration efforts."
                   ],
                   [
                       'name' => 'Energy Supply Risks',
                       'description' => "Risks associated with disruptions in energy supply to utility facilities, including power outages, fuel shortages, or price volatility, impacting operational continuity and costs.",
                       'driver' => "Power grid failures or blackouts affecting electricity supply to utility facilities.
                                    Fluctuations in fuel prices impacting the cost of energy production.
                                    Dependency on imported fuel sources susceptible to supply disruptions or geopolitical tensions.
                                    Limited availability of renewable energy sources during adverse weather conditions.
                                    Regulatory changes impacting energy procurement practices and tariffs."
                   ],
                   [
                       'name' => 'Asset Integrity and Aging Infrastructure',
                       'description' => "Risks associated with the degradation of utility infrastructure over time, leading to reliability issues, leaks, and service interruptions.",
                       'driver' => "Aging equipment and infrastructure susceptible to corrosion, wear, or deterioration.
                                    Lack of preventive maintenance programs or asset management strategies.
                                    Deferred maintenance due to budget constraints or resource limitations.
                                    Inadequate monitoring and inspection of critical assets.
                                    Failure to plan for asset renewal or replacement in a timely manner."
                   ],
                   [
                       'name' => 'Operational Efficiency and Performance',
                       'description' => "Risks related to inefficiencies in utility operations, impacting reliability, cost-effectiveness, and service quality.",
                       'driver' => "Inefficient use of resources such as energy, water, or chemicals.
                                    Suboptimal operational practices or outdated procedures.
                                    Lack of automation or digitalization in utility processes.
                                    Over-reliance on manual interventions leading to errors or delays.
                                    Insufficient investment in technology upgrades or process improvements."
                   ],
                   [
                       'name' => 'Equipment Failure and Downtime',
                       'description' => "Risks associated with the failure or malfunction of critical equipment, leading to unplanned downtime and disruptions in utility operations.",
                       'driver' => "Aging infrastructure and equipment prone to breakdowns.
                                    Inadequate maintenance practices or deferred maintenance.
                                    Mechanical failures or component degradation over time.
                                    Lack of spare parts or inventory management issues.
                                    Human error in equipment operation or maintenance activities."
                   ]
               ]
           ],
           'pm' => [
               'value' => 'Project Management',
               'details' => [
                   [
                       'name' => 'Scope Creep',
                       'description' => "The gradual expansion or addition of project scope beyond its original boundaries, leading to increased costs, delays, and resource strain.",
                       'driver' => "Inadequate definition of project objectives and deliverables.
                                    Changing stakeholder requirements or expectations during project execution.
                                    Poorly managed change control processes allowing unauthorized scope changes.
                                    Lack of clear communication and alignment on project scope among stakeholders.
                                    Unrealistic project estimates leading to underestimated scope and effort."
                   ],
                   [
                       'name' => 'Schedule Delays',
                       'description' => "Risks associated with delays in project milestones or completion dates, impacting overall project timeline and delivery.",
                       'driver' => "Poorly defined project schedules lacking detailed task planning and dependencies.
                                    Resource constraints or bottlenecks affecting task execution and progress.
                                    Unforeseen events such as equipment failures, weather disruptions, or regulatory delays.
                                    Inaccurate estimation of task durations or dependencies during project planning.
                                    Scope changes or rework activities impacting planned timelines."
                   ],
                   [
                       'name' => 'Budget Overruns',
                       'description' => "Risks stemming from exceeding the allocated project budget, leading to financial strain and potential project cancellation.",
                       'driver' => "Inaccurate cost estimation or budgeting during project planning.
                                    Scope changes or additional requirements increasing project costs.
                                    Poor cost control measures allowing expenses to escalate beyond budgeted amounts.
                                    Price fluctuations in labor, materials, or services impacting project costs.
                                    Inadequate monitoring and reporting of project expenditures."
                   ],
                   [
                       'name' => 'Resource Constraints',
                       'description' => "Risks related to insufficient or misallocated resources, including personnel, equipment, and funding, impacting project execution and quality.",
                       'driver' => "Inadequate resource planning and allocation during project initiation and planning phases.
                                    Competing priorities and demands for resources across multiple projects or departments.
                                    Skill shortages or talent gaps affecting project team performance.
                                    Equipment or technology failures leading to resource unavailability.
                                    Budget constraints limiting the acquisition of necessary resources."
                   ],
                   [
                       'name' => 'Stakeholder Management',
                       'description' => "Risks associated with ineffective communication and engagement with project stakeholders, leading to misunderstandings, conflicts, and resistance to project outcomes.",
                       'driver' => "Lack of stakeholder identification and analysis during project initiation.
                                    Insufficient communication channels and mechanisms for engaging stakeholders.
                                    Conflicting interests or priorities among project stakeholders.
                                    Inadequate stakeholder involvement in decision-making processes.
                                    Failure to manage expectations and address concerns among stakeholders."
                   ],
                   [
                       'name' => 'Quality Management',
                       'description' => "Risks related to the failure to meet quality standards and deliverables, leading to rework, customer dissatisfaction, and project failure.",
                       'driver' => "Inadequate quality planning and assurance processes during project initiation.
                                    Poorly defined quality requirements and acceptance criteria.
                                    Lack of skilled personnel or resources for quality assurance activities.
                                    Failure to conduct adequate testing and validation of project deliverables.
                                    Non-compliance with industry standards or regulatory requirements."
                   ],
                   [
                       'name' => 'Enterprise Risk',
                       'description' => "Risks associated with the failure to identify, assess, and mitigate project risks effectively, leading to negative impacts on project objectives.",
                       'driver' => "Incomplete risk identification and assessment during project planning.
                                    Lack of risk response strategies or contingency plans for high-impact risks.
                                    Insufficient monitoring and tracking of identified risks throughout the project lifecycle.
                                    Failure to allocate resources for risk management activities.
                                    Ineffective communication and collaboration among project team members on risk-related issues.Certainly! Here are additional specific risks related to project management:"
                   ],
                   [
                       'name' => 'Communication Breakdowns',
                       'description' => "Risks arising from ineffective communication channels, misinterpretation of information, or lack of clarity in project communications, leading to misunderstandings, delays, and errors.",
                       'driver' => "Poorly defined communication protocols and channels within the project team.
                                    Language barriers or cultural differences impacting communication effectiveness.
                                    Over-reliance on email communication leading to information overload and overlooked messages.
                                    Lack of regular updates and progress reports among project stakeholders.
                                    Failure to address communication gaps or conflicts in a timely manner."
                   ],
                   [
                       'name' => 'Dependencies and Interdependencies',
                       'description' => "Risks associated with dependencies between project tasks, activities, or external factors, leading to delays or disruptions when dependencies are not managed effectively.",
                       'driver' => "Inaccurate identification or oversight of critical dependencies during project planning.
                                    Unforeseen delays or changes in dependent activities impacting project timelines.
                                    External dependencies on suppliers, vendors, or regulatory approvals not adequately managed.
                                    Lack of contingency plans for addressing dependencies that cannot be met.
                                    Complexity and interconnectedness of dependencies across multiple project streams or phases."
                   ],
                   [
                       'name' => 'Procurement and Vendor Management',
                       'description' => "Risks related to the acquisition of goods, services, or resources from external vendors or contractors, including contract disputes, delivery delays, and quality issues.",
                       'driver' => "Inadequate vendor selection and evaluation processes during procurement.
                                    Contractual disputes or disagreements over terms, deliverables, or pricing.
                                    Delays in vendor deliveries or performance impacting project schedules.
                                    Quality issues with vendor-supplied goods or services affecting project outcomes.
                                    Dependency on single-source vendors increasing vulnerability to supply disruptions or failures."
                   ],
                   [
                       'name' => 'Integration Management',
                       'description' => "Risks associated with the integration of various project components, systems, or processes, including technical compatibility issues, data inconsistencies, and performance gaps.",
                       'driver' => "Lack of integration planning and coordination among project teams or stakeholders.
                                    Incompatible technologies or systems leading to data transfer errors or interoperability issues.
                                    Insufficient testing and validation of integrated components or interfaces.
                                    Changes in project requirements impacting integration scope and complexity.
                                    Failure to establish clear ownership and accountability for integration tasks and deliverables."
                   ],
                   [
                       'name' => 'Change Management',
                       'description' => "Risks related to managing changes to project scope, requirements, or objectives, including resistance to change, scope creep, and lack of stakeholder buy-in.",
                       'driver' => "Inadequate change control processes or procedures for managing scope changes.
                                    Stakeholder resistance or reluctance to accept changes to project plans or deliverables.
                                    Unclear impact assessments and communication of change implications.
                                    Failure to prioritize and prioritize change requests based on project objectives.
                                    Lack of alignment between project changes and organizational goals or priorities."
                   ]
               ]
           ],
           'm' => [
               'value' => 'Maintenance',
               'details' => [
                   [
                       'name' => 'Equipment Downtime and Unplanned Failures',
                       'description' => "Risks associated with unexpected equipment failures or breakdowns, leading to unplanned downtime, production losses, and maintenance disruptions.",
                       'driver' => "Inadequate preventive maintenance schedules or procedures.
                                    Aging equipment and infrastructure prone to failures and malfunctions.
                                    Lack of spare parts inventory or delayed procurement processes.
                                    Inefficient asset management practices impacting equipment reliability.
                                    Failure to address recurring issues or root causes of failures."
                   ],
                   [
                       'name' => 'Deferred Maintenance',
                       'description' => "Risks stemming from the postponement or delay of necessary maintenance activities, leading to deteriorating asset condition, increased failure rates, and safety hazards.",
                       'driver' => "Budget constraints limiting investment in maintenance and repairs.
                                    Prioritization of production or operational needs over maintenance requirements.
                                    Lack of visibility into asset condition and performance metrics.
                                    Insufficient workforce or resource capacity for executing maintenance tasks.
                                    Failure to implement proactive maintenance strategies and predictive maintenance technologies."
                   ],
                   [
                       'name' => 'Safety Risks and Regulatory Compliance',
                       'description' => "Risks related to safety hazards and non-compliance with health, safety, and environmental regulations within maintenance operations, leading to accidents, injuries, and regulatory penalties.",
                       'driver' => "Inadequate training and competency programs for maintenance personnel.
                                    Failure to follow safety protocols and procedures during maintenance activities.
                                    Insufficient risk assessments and mitigation measures for high-risk maintenance tasks.
                                    Non-compliance with regulatory requirements for equipment inspections and certifications.
                                    Lack of safety culture and awareness among maintenance teams."
                   ],
                   [
                       'name' => 'Asset Performance Degradation',
                       'description' => "Risks associated with declining asset performance and efficiency over time, leading to decreased productivity, increased energy consumption, and higher maintenance costs.",
                       'driver' => "Lack of condition monitoring and predictive maintenance technologies for early fault detection.
                                    Inefficient asset utilization and suboptimal operational practices.
                                    Normal wear and tear of equipment components without timely replacements.
                                    Inadequate calibration and alignment of equipment leading to performance degradation.
                                    Failure to implement reliability-centered maintenance strategies for critical assets."
                   ],
                   [
                       'name' => 'Budgetary Constraints and Resource Allocation',
                       'description' => "Risks arising from insufficient funding or resources allocated for maintenance activities, leading to deferred maintenance, reduced service levels, and asset reliability issues.",
                       'driver' => "Budget cuts or austerity measures impacting maintenance spending.
                                    Lack of visibility into maintenance costs and cost drivers.
                                    Inaccurate cost estimation and forecasting for maintenance activities.
                                    Competition for resources with other operational or capital projects.
                                    Ineffective prioritization of maintenance needs based on asset criticality and risk."
                   ],
                   [
                       'name' => 'Technology Integration and Adoption',
                       'description' => "Risks associated with the integration and adoption of new technologies, software systems, and maintenance practices within the organization, including compatibility issues, resistance to change, and skill gaps.",
                       'driver' => "Lack of alignment between technology investments and maintenance objectives.
                                    Resistance to change among maintenance personnel due to fear of job displacement or unfamiliarity with new technologies.
                                    Incompatibility between legacy systems and new maintenance management software.
                                    Insufficient training and support for employees transitioning to new maintenance processes and tools.
                                    Failure to realize expected benefits from technology investments due to implementation challenges or adoption barriers.Certainly! Here are additional specific risks related to equipment controls:"
                   ],
                   [
                       'name' => 'Equipment Control System Failures',
                       'description' => "Risks associated with the failure or malfunction of equipment control systems, including PLCs (Programmable Logic Controllers), SCADA (Supervisory Control and Data Acquisition) systems, and DCS (Distributed Control Systems), leading to operational disruptions and safety hazards.",
                       'driver' => "Software bugs or glitches in control system programming leading to unexpected behavior or system crashes.
                                    Hardware failures or malfunctions in control system components such as sensors, actuators, or communication interfaces.
                                    Cybersecurity threats targeting control systems, including malware, ransomware, or unauthorized access.
                                    Insufficient redundancy and failover mechanisms in control system architectures.
                                    Lack of regular maintenance and updates for control system software and firmware."
                   ],
                   [
                       'name' => 'Human Error in Equipment Control',
                       'description' => "Risks stemming from human errors in operating, configuring, or troubleshooting equipment control systems, leading to safety incidents, production losses, and equipment damage.",
                       'driver' => "Inadequate training and competency programs for operators and maintenance personnel responsible for equipment control.
                                    Lack of standardized operating procedures and control system documentation.
                                    Fatigue, distraction, or complacency among control system operators leading to mistakes or oversights.
                                    Misinterpretation of control system data or alarms resulting in inappropriate actions or interventions.
                                    Insufficient supervision and oversight of control system operations during critical tasks or emergency situations."
                   ],
                   [
                       'name' => 'Data Integrity and Accuracy',
                       'description' => "Risks associated with inaccuracies, inconsistencies, or corruption of data within equipment control systems, leading to erroneous decision-making, process deviations, and quality issues",
                       'driver' => "Inadequate data validation and verification mechanisms in control system software.
                                    Data transmission errors or disruptions affecting the integrity of control system communications.
                                    Calibration drift or sensor inaccuracies leading to erroneous measurement data.
                                    Human errors in data entry, manipulation, or recording within control system interfaces.
                                    Cybersecurity attacks targeting control system data integrity, including data manipulation or tampering."
                   ],
                   [
                       'name' => 'Obsolete or Unsupported Equipment Controls',
                       'description' => "Risks arising from the use of outdated or unsupported equipment control technologies, including legacy systems, obsolete software, and discontinued hardware, leading to compatibility issues, security vulnerabilities, and maintenance challenges",
                       'driver' => "Aging infrastructure and control systems reaching end-of-life or end-of-support from manufacturers.
                                    Lack of vendor support or updates for legacy control system components.
                                    Incompatibility between legacy control systems and modern IT environments or communication protocols.
                                    Difficulty sourcing replacement parts or components for obsolete equipment controls.
                                    Exposure to cybersecurity risks and vulnerabilities due to unpatched or unsupported software and firmware."
                   ],
                   [
                       'name' => 'Regulatory Compliance for Equipment Controls',
                       'description' => "Risks related to non-compliance with regulatory requirements and industry standards governing equipment controls, including safety regulations, cybersecurity guidelines, and performance standards.",
                       'driver' => "Evolving regulatory landscape and compliance requirements for equipment controls in various industries.
                                    Non-compliance with safety standards and regulations governing control system design, installation, and operation.
                                    Failure to implement cybersecurity best practices and controls to protect control systems from cyber threats.
                                    Lack of documentation and evidence demonstrating compliance with regulatory requirements for equipment controls.
                                    Legal and financial penalties resulting from non-compliance with equipment control regulations and standards."
                   ]
               ]
           ],
           're' => [
               'value' => 'Reliability',
               'details' => [
                   [
                       'name' => 'Asset Reliability Failures',
                       'description' => "Risks associated with the failure of assets to perform their intended functions reliably, leading to operational disruptions, production losses, and maintenance challenges.",
                       'driver' => "Aging infrastructure and equipment susceptible to degradation and failures.
                                    Inadequate preventive maintenance programs or asset management practices.
                                    Lack of reliability-centered maintenance strategies focusing on critical asset failure modes.
                                    Insufficient resources or expertise for condition monitoring and predictive maintenance.
                                    Failure to address root causes of asset failures and implement corrective actions."
                   ],
                   [
                       'name' => 'Critical Component Failures',
                       'description' => "Risks stemming from the failure of critical components within assets or systems, leading to cascading failures, safety hazards, and significant downtime.",
                       'driver' => "Lack of redundancy and failover mechanisms for critical components.
                                    Equipment wear and tear leading to fatigue failures or material degradation.
                                    Inadequate spare parts inventory or supply chain disruptions for critical components.
                                    Failure to monitor and detect early warning signs of impending component failures.
                                    Dependency on single-source suppliers for critical components increasing vulnerability to supply disruptions."
                   ],
                   [
                       'name' => 'Systemic Reliability Issues',
                       'description' => "Risks arising from systemic weaknesses in asset design, operation, or maintenance practices, leading to recurrent reliability problems and chronic performance issues.",
                       'driver' => "Design flaws or deficiencies in assets impacting long-term reliability and performance.
                                    Suboptimal operational practices or operating conditions leading to accelerated degradation.
                                    Ineffective maintenance strategies failing to address underlying reliability issues.
                                    Lack of continuous improvement initiatives to address systemic reliability problems.
                                    Failure to incorporate lessons learned from previous reliability incidents into future practices."
                   ],
                   [
                       'name' => 'Unplanned Downtime and Outages',
                       'description' => "Risks associated with unplanned interruptions in asset availability, resulting in production losses, revenue impact, and customer dissatisfaction.",
                       'driver' => "Equipment failures or malfunctions causing unexpected downtime.
                                    Inadequate maintenance planning and scheduling leading to missed inspections or overhauls.
                                    Lack of visibility into asset health and performance metrics for early fault detection.
                                    External factors such as weather events, utility disruptions, or supply chain interruptions.
                                    Insufficient redundancy or backup systems to mitigate the impact of unplanned downtime."
                   ],
                   [
                       'name' => 'Reliability-Centered Maintenance (RCM) Risks',
                       'description' => "Risks associated with the implementation and effectiveness of reliability-centered maintenance strategies, including challenges in prioritizing maintenance tasks, resource allocation, and performance tracking.",
                       'driver' => "Incomplete or inaccurate asset criticality assessments affecting prioritization of maintenance activities.
                                    Lack of alignment between RCM strategies and organizational goals or operational priorities.
                                    Insufficient resources or capabilities for executing RCM-based maintenance programs.
                                    Difficulty in measuring and tracking the effectiveness of RCM initiatives.
                                    Resistance to change or cultural barriers impacting the adoption of RCM principles and practices."
                   ],
                   [
                       'name' => 'Lifecycle Management Risks',
                       'description' => "Risks related to managing asset reliability throughout their lifecycle, including design, procurement, operation, maintenance, and decommissioning phases.",
                       'driver' => "Poor asset design or selection criteria leading to reliability issues during operation.
                                    Inadequate consideration of reliability requirements in procurement specifications.
                                    Challenges in maintaining reliability as assets age and approach end-of-life.
                                    Lack of planning for asset upgrades, replacements, or decommissioning.
                                    Failure to capture and transfer reliability knowledge between lifecycle phases and stakeholders."
                   ]
               ]
           ],
           'it' => [
               'value' => 'Information Technology',
               'details' => [
                   [
                       'name' => 'Cybersecurity Threats',
                       'description' => "Risks associated with malicious cyber threats targeting ICT systems, including data breaches, malware infections, ransomware attacks, and unauthorized access.",
                       'driver' => "Sophisticated cyberattacks exploiting vulnerabilities in ICT infrastructure and software.
                                    Insider threats, including employee negligence or malicious intent.
                                    Lack of robust cybersecurity measures, including firewalls, intrusion detection systems, and encryption.
                                    Inadequate patch management and software updates leaving systems vulnerable to known exploits.
                                    Social engineering attacks targeting users to gain unauthorized access to systems or sensitive information."
                   ],
                   [
                       'name' => 'Data Privacy and Compliance',
                       'description' => "Risks related to the unauthorized access, use, or disclosure of sensitive data, leading to legal and regulatory violations, financial penalties, and reputational damage.",
                       'driver' => "Non-compliance with data protection regulations such as GDPR, CCPA, or HIPAA.
                                    Inadequate data encryption, access controls, and data loss prevention measures.
                                    Insider threats or data breaches resulting from employee errors or malicious actions.
                                    Lack of transparency and accountability in data handling practices.
                                    Failure to implement data privacy impact assessments and incident response plans."
                   ],
                   [
                       'name' => 'System Downtime and Availability',
                       'description' => "Risks associated with disruptions in ICT services, including system outages, network failures, and service degradation, impacting business operations and user productivity.",
                       'driver' => "Infrastructure failures or hardware malfunctions affecting system availability.
                                    Network congestion or bandwidth limitations causing service degradation.
                                    Inadequate redundancy and failover mechanisms for critical ICT components.
                                    Cyberattacks targeting availability through DDoS (Distributed Denial of Service) attacks or ransomware.
                                    Lack of proactive monitoring and alerting systems for detecting and mitigating downtime."
                   ],
                   [
                       'name' => 'Business Continuity and Disaster Recovery',
                       'description' => "Risks related to the inability to recover ICT systems and data in the event of disasters, including natural disasters, cyberattacks, hardware failures, or human errors.",
                       'driver' => "Inadequate backup and recovery procedures for critical data and systems.
                                    Lack of comprehensive disaster recovery plans and testing protocols.
                                    Dependency on single points of failure in ICT infrastructure and architecture.
                                    Insufficient redundancy and failover mechanisms for ensuring business continuity.
                                    Failure to prioritize and allocate resources for disaster recovery preparedness."
                   ],
                   [
                       'name' => 'Vendor and Supply Chain Risks',
                       'description' => "Risks associated with third-party vendors and service providers, including supply chain disruptions, contractual disputes, and vendor performance issues impacting ICT operations.",
                       'driver' => "Dependency on external vendors for ICT hardware, software, and cloud services.
                                    Supply chain disruptions affecting the availability of ICT components and equipment.
                                    Inadequate due diligence in vendor selection and contract negotiation.
                                    Vendor lock-in and lack of flexibility in vendor relationships.
                                    Insufficient monitoring and oversight of vendor performance and compliance."
                   ],
                   [
                       'name' => 'IT Governance and Compliance',
                       'description' => "Risks related to ineffective IT governance practices, including inadequate policies, procedures, and controls governing ICT operations, leading to compliance failures and operational inefficiencies.",
                       'driver' => "Lack of alignment between ICT strategy and organizational goals and objectives.
                                    Incomplete or outdated IT policies and procedures, including IT security policies, change management procedures, and access controls.
                                    Non-compliance with industry regulations and standards governing ICT operations, such as ISO 27001, NIST, or PCI DSS.
                                    Weaknesses in IT risk management processes, including risk assessment, mitigation, and monitoring.
                                    Insufficient transparency and accountability in ICT decision-making and oversight."
                   ],
                   [
                       'name' => 'IT Governance and Compliance',
                       'description' => "Risks related to ineffective IT governance practices, including inadequate policies, procedures, and controls governing ICT operations, leading to compliance failures and operational inefficiencies.",
                       'driver' => "Lack of alignment between ICT strategy and organizational goals and objectives.
                                    Incomplete or outdated IT policies and procedures, including IT security policies, change management procedures, and access controls.
                                    Non-compliance with industry regulations and standards governing ICT operations, such as ISO 27001, NIST, or PCI DSS.
                                    Weaknesses in IT risk management processes, including risk assessment, mitigation, and monitoring.
                                    Insufficient transparency and accountability in ICT decision-making and oversight."
                   ],
                   [
                       'name' => 'Technology Obsolescence and Legacy Systems',
                       'description' => "Risks associated with outdated technology infrastructure, legacy systems, and obsolete software, leading to compatibility issues, performance degradation, and security vulnerabilities.",
                       'driver' => "Lack of investment in technology upgrades and modernization initiatives.
                                    Dependency on unsupported or end-of-life software and hardware platforms.
                                    Challenges in integrating legacy systems with newer technologies and applications.
                                    Security risks associated with unpatched vulnerabilities in outdated software.
                                    Difficulty in finding skilled personnel to support and maintain legacy systems."
                   ],
                   [
                       'name' => 'Cloud Computing Risks',
                       'description' => "Risks related to the adoption and use of cloud computing services, including data security concerns, compliance challenges, and dependency on third-party providers.",
                       'driver' => "Data breaches and unauthorized access to sensitive information stored in the cloud.
                                    Compliance issues arising from data sovereignty, privacy regulations, and legal jurisdiction.
                                    Service interruptions and outages affecting cloud-based applications and services.
                                    Lack of transparency and control over cloud provider security practices and data handling.
                                    Vendor lock-in and challenges in migrating data and workloads between cloud providers."
                   ],
                   [
                       'name' => 'Digital Transformation Risks',
                       'description' => "Risks associated with digital transformation initiatives, including disruptive technologies, organizational change, and cultural resistance, impacting business processes and ICT capabilities.",
                       'driver' => "Disruption of traditional business models and industry paradigms by emerging technologies.
                                    Resistance to change among employees and stakeholders impacted by digital transformation.
                                    Inadequate planning and governance for digital initiatives, leading to scope creep and project delays.
                                    Skills gaps and talent shortages in emerging technology areas such as AI, IoT, and data analytics.
                                    Cybersecurity risks associated with the proliferation of digital touchpoints and connected devices."
                   ],
                   [
                       'name' => 'Data Management Risks',
                       'description' => "Risks related to the management, storage, and governance of data assets within the organization, including data quality issues, data breaches, and regulatory compliance.",
                       'driver' => "Data breaches resulting from unauthorized access, insider threats, or accidental disclosures.
                                    Data integrity issues arising from errors, omissions, or inconsistencies in data records.
                                    Non-compliance with data protection regulations and privacy laws, leading to fines and penalties.
                                    Inadequate data governance practices, including data classification, access controls, and data lifecycle management.
                                    Lack of transparency and accountability in data handling processes and procedures."
                   ],
                   [
                       'name' => 'ICT Project Management Risks',
                       'description' => "Risks associated with the planning, execution, and delivery of ICT projects, including scope creep, budget overruns, and schedule delays.",
                       'driver' => "Poorly defined project requirements and objectives, leading to changes and revisions during project execution.
                                    Inaccurate estimation of project costs, resources, and timelines during project planning.
                                    Inadequate project management methodologies and governance structures.
                                    Dependencies on external vendors, subcontractors, or third-party suppliers impacting project delivery.
                                    Communication breakdowns and stakeholder conflicts affecting project coordination and alignment."
                   ]
               ]
           ],
        ],
        'racm' => [
            'risk_factor_name' => [
                'Delivery performance' => 'Delays in deliveries impacting production schedules and customer satisfaction.',
                'Excess Inventory/stock levels' => 'Excess inventory leads to increased holding costs.',
                'Stock outs' => 'Stock outs leading to potential obsolescence, or lost sales opportunities.',
                'Raw Material Availability (Local)' => 'Risk of supply shortages or disruptions in locally sourced raw materials impacting production schedules and product quality.',
                'Raw Material Availability (International)' => 'Supply chain disruptions with globally sourced raw materials.',
                'Market volatility and competition' => 'Risk of sales fluctuations and market share erosion due to unpredictable market conditions and intense competition.',
                'Effectiveness of marketing strategies' => 'Poor brand visibility, low lead generation, and reduced sales.',
                'Customer satisfaction' => 'Dissatisfied customers lead to negative reviews, decreased repeat business',
                'Customer retention' => 'Losing loyal customers to competitors due to inadequate retention strategies',
                'Reputation management and brand perception' => 'Risk of negative publicity, poor brand image',
                'Equipment reliability' => 'Production disruptions or downtime due to equipment failures, breakdowns, or malfunctions, impacting production schedules and product quality.',
                'Delayed / Ineffective Maintenance' => 'Increased downtime due to delays or inadequacies in maintenance practices, leading to higher maintenance costs and lower productivity.',
                'Product defects' => 'Manufacturing defects or non-conformities in products, leading to quality issues, customer complaints, and potential recalls, damaging brand reputation and revenue.',
                'Production capacity and efficiency' => 'Inefficiencies in production processes, limiting output capacity and increasing production costs per unit, affecting competitiveness and profitability.',
                'Downtime' => 'Production stoppages or downtime due to operational disruptions, resulting in lost production time, revenue.',
                'Poor Quality' => 'Producing goods that do not meet quality standards or customer expectations.',
                'Customer complaints and feedback' => 'Receiving negative feedback or complaints',
                'Workforce planning and succession risks' => 'Inadequate workforce planning and succession strategies, for key personnel',
                'Employee turnover and retention rates' => 'Employee turnover rates',
                'Compliance with labor laws and regulations' => 'Non-compliance with labor laws, regulations, and employment standards, leads to legal penalties, lawsuits, and reputational damage.',
                'Training and development effectiveness' => 'Insufficient employee skills, knowledge gaps, and reduced performance and productivity.',
                'Workplace diversity and inclusion' => 'Failing to create an inclusive work environment that promotes diversity and equality, leads to discrimination claims, employee dissatisfaction, and reputational harm.',
                'Cash flow management and liquidity' => 'Liquidity shortages',
                'Fraud and embezzlement' => 'Fraudulent activities or embezzlement within the organization, resulting in financial losses.',
                'Tax compliance and regulatory changes' => 'Non-compliance with tax laws and regulations, leads to penalties, fines, and reputational damage.',
                'Revenue' => 'Fluctuations or decline in revenue',
                'Accounts receivable' => 'Delayed or non-payment by customers',
                'Accounts payable' => 'Delayed payments to suppliers or creditors, leads to strained supplier relationships',
                'Data security and privacy risks' => 'Data breaches, unauthorized access, or loss of sensitive information',
                'Cyber security threats and vulnerabilities' => 'Cyber attacks, malware infections',
                'IT infrastructure reliability and resilience' => 'Risk of IT system failures, outages, or disruptions, leading to downtime, loss of productivity, and potential data loss.',
                'Workplace safety hazards and accident rates' => 'Workplace accidents, injuries, or occupational illnesses.',
                'Environmental impact and sustainability rates' => 'Environmental harm, pollution, or non-compliance with environmental regulations.',
                'Security threats (e.g., theft, vandalism)' => 'Security incidents such as theft, vandalism, or unauthorized access to facilities.',
                'Compliance with health and safety regulations' => 'Non-compliance with health and safety laws and regulations.',
            ]
        ]
    ],
    'corrective_management' => [
        'progresses' => [
            'not_started' => 'Not Started',
            'in_progress_0' => 'In Progress (0%)',
            'in_progress_25' => 'In Progress (25%)',
            'in_progress_50' => 'In Progress (50%)',
            'in_progress_75' => 'In Progress (75%)',
            'completed' => 'Completed',
        ]
    ],
    'complaint_management' => [
        'progresses' => [
            'not_started' => 'Not Started',
            'in_progress_0' => 'In Progress (0%)',
            'in_progress_25' => 'In Progress (25%)',
            'in_progress_50' => 'In Progress (50%)',
            'in_progress_75' => 'In Progress (75%)',
            'completed' => 'Completed',
        ]
    ],
    'objectives' => [
        'category' => [
            'hr' => [
                'value' => 'Human Resource',
                'details' => [
                     [
                        'name' => 'Employee Turnover Rate',
                        'description' => 'Maintain a turnover rate below 10% annually. Measure the percentage of employees who leave the organization within a specific period.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Divide the number of employees who left during the period by the average total number of employees during the same period, then multiply by 100.',
                        'resources' => 'HR database or HRIS (Human Resources Information System) to track employee movements, exit interviews, HR analytics tools.'
                     ],
                     [
                         'name' => 'Recruitment Efficiency',
                         'description' => 'Achieve a time-to-fill rate of 30 days for vacant positions. Assess the effectiveness and efficiency of the recruitment process.',
                         'target' => '30',
                         'unit' => 'days',
                         'Methodology' => 'Calculate the average time taken to fill vacant positions from the date of posting to the date of hire.',
                         'resources' => "Applicant tracking system (ATS), recruitment software, HR team's time and effort, hiring manager involvement."
                     ],
                     [
                         'name' => 'Training Effectiveness',
                         'description' => 'Achieve a post-training performance improvement rate of 20%. Evaluate the impact and effectiveness of employee training and development programs.',
                         'target' => '20',
                         'unit' => '%',
                         'Methodology' => 'Measure the increase in employee performance or competency levels after completing training programs.',
                         'resources' => "Training materials, trainers, learning management system (LMS), pre and post-training assessments, feedback surveys."
                     ],
                     [
                         'name' => 'Employee Engagement',
                         'description' => 'Maintain an employee engagement score of 75% or higher. Measure the level of employee engagement and satisfaction within the organization.',
                         'target' => '75',
                         'unit' => '%',
                         'Methodology' => 'Conduct regular employee engagement surveys using standardized questionnaires or tools.',
                         'resources' => "Employee engagement survey software, HR team's time for survey administration and analysis, action planning resources."
                     ],
                     [
                         'name' => 'HR Budget Adherence',
                         'description' => 'Maintain HR expenses within 5% variance of the allocated budget. Monitor and control HR expenditures to ensure alignment with the allocated budget.',
                         'target' => '5',
                         'unit' => '%',
                         'Methodology' => 'Compare actual HR expenditures against the budgeted amounts on a regular basis.',
                         'resources' => "Budgeting software or financial management tools, HR finance expertise, collaboration with finance department."
                     ],
                     [
                         'name' => 'Diversity and Inclusion Metrics',
                         'description' => 'Increase diversity representation by 10% across all levels within three years. Track diversity and inclusion metrics to ensure a diverse and inclusive workplace.',
                         'target' => '10',
                         'unit' => '%',
                         'Methodology' => 'Monitor demographic data such as gender, ethnicity, age, and other diversity dimensions within the workforce.',
                         'resources' => "HRIS with diversity reporting capabilities, diversity and inclusion training programs, diversity recruitment strategies."
                     ],
                     [
                         'name' => 'HR Process Efficiency',
                         'description' => 'Achieve a 20% reduction in HR process cycle times. Improve the efficiency of HR processes to enhance productivity and reduce administrative burdens.',
                         'target' => '20',
                         'unit' => '%',
                         'Methodology' => 'Measure the time taken to complete key HR processes such as onboarding, performance reviews, and payroll processing.',
                         'resources' => "Process mapping tools, automation software, HR process optimization team."
                     ],
                     [
                         'name' => 'Compliance Adherence',
                         'description' => 'Maintain a 95% compliance rate with HR-related laws and regulations. Ensure compliance with labor laws, regulations, and internal policies.',
                         'target' => '95',
                         'unit' => '%',
                         'Methodology' => 'Conduct regular audits and reviews of HR practices, policies, and documentation.',
                         'resources' => "Compliance management software, legal expertise, HR compliance training."
                     ],
                ]
            ],
            'sam' => [
                'value' => 'Sales and Marketing',
                'details' => [
                    [
                        'name' => 'Sales Revenue Growth',
                        'description' => 'Increase sales revenue by 15% annually through effective sales strategies and customer acquisition efforts.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => "Compare current year sales revenue to the previous year's revenue and calculate the percentage increase.",
                        'resources' => "Sales data analytics tools, CRM (Customer Relationship Management) software, sales training programs."
                    ],
                    [
                        'name' => 'Customer Acquisition Rate',
                        'description' => 'Achieve a 10% increase in the number of new customers acquired through targeted marketing campaigns and lead generation activities.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Compare the number of new customers acquired within a specific period to the previous period and calculate the percentage change.',
                        'resources' => "Marketing automation tools, lead generation software, customer acquisition team."
                    ],
                    [
                        'name' => 'Conversion Rate',
                        'description' => 'Improve the conversion rate by 20% through optimizing sales processes and enhancing customer engagement strategies.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of leads or prospects that convert into paying customers within a specific period.',
                        'resources' => "Sales pipeline management software, CRM system, sales enablement resources."
                    ],
                    [
                        'name' => 'Customer Lifetime Value (CLV)',
                        'description' => 'Increase the average CLV by 10% by fostering long-term customer relationships and maximizing customer value.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the average revenue generated from a customer over their entire relationship with the company.',
                        'resources' => "Customer segmentation tools, customer relationship management software, customer retention strategies."
                    ],
                    [
                        'name' => 'Market Share Growth',
                        'description' => 'Expand market share by 5% through strategic market penetration and competitive positioning initiatives.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => "Compare the company's sales revenue or units sold to the total market sales within the industry.",
                        'resources' => "Market research tools, competitive analysis, product development team."
                    ],
                    [
                        'name' => 'Marketing ROI (Return on Investment)',
                        'description' => 'Achieve a marketing ROI of 20% by optimizing marketing campaigns and allocating resources to high-performing channels.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Calculate the ratio of marketing revenue generated to the total marketing costs incurred.',
                        'resources' => "Marketing analytics tools, attribution modeling software, marketing budget allocation strategy."
                    ],
                    [
                        'name' => 'Brand Awareness',
                        'description' => 'Increase brand awareness by 15% through targeted brand-building activities and marketing campaigns.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Measure brand recognition, recall, and familiarity among the target audience through surveys or brand tracking studies.',
                        'resources' => "Brand monitoring tools, brand identity development, advertising and promotional materials."
                    ],
                    [
                        'name' => 'Customer Satisfaction',
                        'description' => 'Maintain a customer satisfaction score of 85% or higher by delivering exceptional customer service and addressing customer feedback effectively.',
                        'target' => '85',
                        'unit' => '%',
                        'Methodology' => 'Collect customer feedback through surveys, reviews, and feedback forms to assess overall satisfaction levels.',
                        'resources' => "Customer feedback management software, customer support team, customer experience improvement initiatives."
                    ],
                ]
            ],
            'sc' => [
                'value' => 'Supply chain',
                'details' => [
                    [
                        'name' => 'Inventory Turnover Rate',
                        'description' => 'Increase inventory turnover rate by 10% annually to optimize inventory management and reduce carrying costs.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the ratio of cost of goods sold to average inventory value.',
                        'resources' => "Inventory management software, ERP system, supply chain analytics tools."
                    ],
                    [
                        'name' => 'On-Time Delivery Performance',
                        'description' => 'Achieve a 95% on-time delivery rate to customers to enhance customer satisfaction and loyalty.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of orders delivered to customers on or before the scheduled delivery date.',
                        'resources' => "Transportation management system (TMS), order tracking system, logistics team coordination."
                    ],
                    [
                        'name' => 'Supplier Performance',
                        'description' => 'Maintain a supplier performance score of 90% or higher by evaluating supplier delivery, quality, and reliability.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Assess supplier performance through key performance indicators such as delivery accuracy, lead times, and product quality.',
                        'resources' => "Supplier scorecard system, supplier relationship management (SRM) tools, supplier audits."
                    ],
                    [
                        'name' => 'Supply Chain Cost Efficiency',
                        'description' => 'Reduce supply chain costs by 5% annually through process optimization and cost-saving initiatives.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Track and analyze supply chain costs including transportation, warehousing, and procurement expenses.',
                        'resources' => "Cost accounting system, supply chain cost analysis tools, cross-functional team collaboration."
                    ],
                    [
                        'name' => 'Inventory Accuracy',
                        'description' => 'Improve inventory accuracy to 98% to minimize stockouts, excess inventory, and inventory write-offs.',
                        'target' => '98',
                        'unit' => '%',
                        'Methodology' => 'Conduct regular cycle counts, inventory reconciliations, and root cause analysis for inventory discrepancies.',
                        'resources' => "Inventory management software, barcode scanning technology, inventory control procedures."
                    ],
                    [
                        'name' => 'Lead Time Reduction',
                        'description' => 'Reduce lead times by 15% to enhance responsiveness and agility in the supply chain.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Analyze and streamline processes across the supply chain to minimize delays and waiting times.',
                        'resources' => "Process mapping tools, supply chain optimization software, cross-functional process improvement teams."
                    ],
                    [
                        'name' => 'Sustainability Performance',
                        'description' => 'Improve sustainability performance by achieving a 20% reduction in carbon emissions and waste generation.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure and monitor environmental impact metrics such as carbon footprint, waste diversion rate, and energy consumption.',
                        'resources' => "Environmental management system (EMS), sustainability reporting tools, green supply chain initiatives."
                    ],
                    [
                        'name' => 'Perfect Order Rate',
                        'description' => 'Improve the perfect order rate to 95% by ensuring orders are delivered accurately, on time, and in full, thereby enhancing customer satisfaction and loyalty.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of orders that are delivered without errors, damages, or delays.',
                        'resources' => "Order management system, order accuracy tracking tools, collaboration with logistics partners."
                    ],
                    [
                        'name' => 'Inventory Days of Supply',
                        'description' => 'Reduce inventory days of supply by 10% to minimize excess inventory and improve working capital efficiency.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the average number of days it takes to sell or use up inventory.',
                        'resources' => "Inventory management software, demand forecasting tools, inventory optimization techniques."
                    ],
                    [
                        'name' => 'Warehouse Space Utilization',
                        'description' => 'Increase warehouse space utilization to 90% by optimizing storage layouts, inventory placement, and warehouse operations.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of available warehouse space that is utilized for storing inventory.',
                        'resources' => "Warehouse management system (WMS), space utilization analysis tools, warehouse layout optimization."
                    ],
                    [
                        'name' => 'Supplier Lead Time Reduction',
                        'description' => 'Reduce supplier lead times by 20% to improve supply chain agility and responsiveness to customer demand fluctuations.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Analyze and streamline supplier processes to minimize the time it takes to fulfill orders or deliver materials.',
                        'resources' => "Supplier collaboration tools, supplier performance metrics tracking, supplier relationship management."
                    ],
                    [
                        'name' => 'Cash-to-Cash Cycle Time',
                        'description' => 'Reduce the cash-to-cash cycle time by 15% to accelerate cash flow and improve financial performance.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Calculate the average time it takes for cash to flow from the purchase of inventory to the collection of accounts receivable.',
                        'resources' => "Financial management software, supply chain finance strategies, cross-functional collaboration with finance."
                    ],
                    [
                        'name' => 'Return on Investment (ROI) in Logistics',
                        'description' => 'Achieve a 10% increase in ROI in logistics investments by optimizing transportation, warehousing, and distribution processes.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the ratio of the financial return generated from logistics investments to the cost of those investments.',
                        'resources' => "Logistics analytics tools, cost-benefit analysis techniques, continuous improvement initiatives."
                    ],
                    [
                        'name' => 'Supply Chain Resilience',
                        'description' => 'Enhance supply chain resilience by implementing risk mitigation strategies and achieving a 95% resilience rating against potential disruptions.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Assess and address vulnerabilities in the supply chain to minimize the impact of disruptions on operations and customer service.',
                        'resources' => "Supply chain risk assessment tools, business continuity planning, collaboration with suppliers and partners."
                    ],
                ]
            ],
            'lg' => [
                'value' => 'Logistics',
                'details' => [
                    [
                        'name' => 'On-Time Delivery Performance',
                        'description' => 'Maintain an on-time delivery rate of 95% to ensure shipments reach their destinations according to schedule, thus meeting customer expectations and minimizing disruptions.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of shipments delivered on or before the scheduled delivery date.',
                        'resources' => "Transportation management system (TMS), real-time tracking technology, carrier performance data."
                    ],
                    [
                        'name' => 'Freight Cost per Unit',
                        'description' => 'Reduce the freight cost per unit by 10% to optimize transportation expenses and improve cost efficiency in logistics operations.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the average cost of freight per unit of goods shipped, considering factors such as distance, mode of transport, and shipment volume.',
                        'resources' => "Freight auditing software, carrier rate negotiation, route optimization tools."
                    ],
                    [
                        'name' => 'Order Fulfillment Cycle Time',
                        'description' => 'Decrease the order fulfillment cycle time by 20% to expedite order processing and improve customer responsiveness.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the time it takes from receiving an order to delivering the products to the customer.',
                        'resources' => "Warehouse management system (WMS), order processing automation, cross-functional collaboration."
                    ],
                    [
                        'name' => 'Inventory Accuracy',
                        'description' => 'Improve inventory accuracy to 98% to minimize stockouts, overstocking, and order fulfillment errors, thereby enhancing customer satisfaction.',
                        'target' => '98',
                        'unit' => '%',
                        'Methodology' => 'Conduct regular cycle counts and reconcile inventory records with physical counts to identify and correct discrepancies.',
                        'resources' => "Inventory management software, barcode scanning technology, inventory control procedures."
                    ],
                    [
                        'name' => 'Dock-to-Stock Time',
                        'description' => 'Reduce dock-to-stock time by 15% to streamline receiving processes and expedite the availability of goods for order fulfillment.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Measure the time it takes to unload, inspect, and stock incoming shipments upon arrival at the warehouse.',
                        'resources' => "Receiving dock optimization, cross-docking strategies, receiving area layout improvements."
                    ],
                    [
                        'name' => 'Order Accuracy Rate',
                        'description' => 'Maintain an order accuracy rate of 99.5% to minimize order picking errors, returns, and customer dissatisfaction.',
                        'target' => '99.5',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of orders that are picked, packed, and shipped correctly without errors or discrepancies.',
                        'resources' => "Pick-to-light systems, order picking optimization, quality control checks."
                    ],
                    [
                        'name' => 'Carrier Performance',
                        'description' => 'Ensure carrier performance meets or exceeds agreed-upon service levels to uphold transportation quality standards and customer service levels.',
                        'target' => '',
                        'unit' => '%',
                        'Methodology' => 'Evaluate carrier performance based on criteria such as on-time delivery, transit time, and cargo condition.',
                        'resources' => "Carrier scorecard system, performance monitoring tools, collaboration with transportation partners."
                    ],
                    [
                        'name' => 'Sustainability in Logistics',
                        'description' => 'Enhance sustainability practices in logistics operations, aiming for a 20% reduction in carbon emissions and fuel consumption to minimize environmental impact.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure and reduce greenhouse gas emissions, fuel usage, and energy consumption through fuel-efficient vehicles, route optimization, and alternative energy sources.',
                        'resources' => "Green logistics initiatives, carbon footprint tracking tools, sustainability reporting frameworks."
                    ],
                ]
            ],
            'prd' => [
                'value' => 'Production',
                'details' => [
                    [
                        'name' => 'Overall Equipment Effectiveness (OEE)',
                        'description' => 'Improve overall equipment effectiveness to 85% by minimizing equipment downtime, reducing production losses, and optimizing machine performance.',
                        'target' => '85',
                        'unit' => '%',
                        'Methodology' => 'Calculate the OEE by multiplying availability, performance, and quality rates.',
                        'resources' => "Production monitoring software, machine maintenance schedule, performance analysis tools."
                    ],
                    [
                        'name' => 'Production Cycle Time',
                        'description' => 'Reduce production cycle time by 20% to increase manufacturing efficiency and meet customer demand more quickly.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the time it takes to complete one cycle of production, from raw material input to finished product output.',
                        'resources' => "Time-study analysis, process optimization techniques, production scheduling software."
                    ],
                    [
                        'name' => 'Yield Rate',
                        'description' => 'Increase yield rate to 95% by minimizing production defects, rework, and scrap, thereby improving product quality and reducing waste.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of good units produced compared to the total units started or processed.',
                        'resources' => "Quality control measures, defect tracking system, root cause analysis tools."
                    ],
                    [
                        'name' => 'Production Cost per Unit',
                        'description' => 'Reduce production cost per unit by 10% to improve cost efficiency and competitiveness in the market.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the average cost incurred to produce one unit of product, including material, labor, and overhead costs.',
                        'resources' => "Cost accounting system, cost reduction initiatives, process optimization strategies."
                    ],
                    [
                        'name' => 'Downtime Reduction',
                        'description' => 'Minimize unplanned downtime by 15% to ensure continuous production and maximize equipment utilization.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Track and analyze causes of downtime, implement preventive maintenance, and optimize production schedules.',
                        'resources' => "Downtime tracking system, predictive maintenance tools, maintenance scheduling software."
                    ],
                    [
                        'name' => 'Capacity Utilization Rate',
                        'description' => 'Increase capacity utilization rate to 90% by optimizing production schedules, balancing workloads, and eliminating bottlenecks.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of actual production output compared to the maximum possible output.',
                        'resources' => "Production planning software, capacity analysis tools, bottleneck identification techniques."
                    ],
                    [
                        'name' => 'Employee Productivity',
                        'description' => 'Improve employee productivity by 10% through training, skill development, and implementing efficient work practices.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Measure the output produced by each employee within a specified time period.',
                        'resources' => "Training programs, performance tracking systems, employee feedback mechanisms."
                    ],
                    [
                        'name' => 'On-Time Delivery Performance',
                        'description' => 'Achieve an on-time delivery rate of 95% to meet customer delivery commitments and enhance customer satisfaction.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of orders or products delivered to customers on or before the agreed-upon delivery date.',
                        'resources' => "Production scheduling tools, order tracking system, logistics coordination."
                    ],
                    [
                        'name' => 'Setup Time Reduction',
                        'description' => 'Decrease setup time by 25% to minimize changeover durations between production runs, thus increasing production flexibility and responsiveness.',
                        'target' => '25',
                        'unit' => 'minutes',
                        'Methodology' => 'Measure the time it takes to switch equipment or processes from producing one product to another.',
                        'resources' => "Setup time analysis tools, SMED (Single-Minute Exchange of Die) techniques, standard operating procedures (SOPs) documentation."
                    ],
                    [
                        'name' => 'First Pass Yield (FPY)',
                        'description' => 'Improve first pass yield to 95% by reducing defects and rework, thereby enhancing product quality and reducing manufacturing costs.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of products that pass quality inspection on the first attempt without requiring rework or repairs.',
                        'resources' => "Quality control measures, process improvement initiatives, root cause analysis techniques."
                    ],
                    [
                        'name' => 'Production Flexibility',
                        'description' => 'Increase production flexibility to accommodate changes in demand or product mix, aiming for a 20% reduction in lead time for new product introductions.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the time it takes to adapt production processes or reconfigure equipment for new products or order specifications.',
                        'resources' => "Agile manufacturing practices, cross-training of employees, flexible production layouts."
                    ],
                    [
                        'name' => 'Scrap and Rework Reduction',
                        'description' => 'Reduce scrap and rework rates by 15% to minimize material waste and improve overall production efficiency.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of defective or non-conforming products that require rework or are scrapped during production.',
                        'resources' => "Root cause analysis tools, quality improvement initiatives, employee training on quality standards."
                    ],
                    [
                        'name' => 'Machine Downtime Analysis',
                        'description' => 'Implement proactive machine downtime analysis to reduce unplanned downtime events by 20%, thereby maximizing equipment uptime and productivity.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Analyze historical downtime data, identify recurring issues, and implement preventive maintenance measures to minimize equipment failures.',
                        'resources' => "Downtime tracking system, predictive maintenance software, maintenance scheduling tools."
                    ],
                    [
                        'name' => 'Production Output Variance',
                        'description' => 'Minimize production output variance to within ±5% of planned targets to ensure consistency and reliability in meeting production goals.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Measure the deviation between actual production output and planned targets, analyzing factors contributing to variance.',
                        'resources' => "Production planning and scheduling tools, variance analysis techniques, continuous improvement initiatives."
                    ],
                    [
                        'name' => 'Work-in-Progress (WIP) Inventory Reduction',
                        'description' => 'Reduce work-in-progress inventory levels by 15% to streamline production flow, minimize lead times, and improve cash flow.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Analyze WIP inventory levels at various production stages and implement lean manufacturing principles to eliminate waste and excess inventory.',
                        'resources' => "Value stream mapping, Kanban systems, pull-based production scheduling."
                    ],
                ]
            ],
            'op' => [
                'value' => 'Operations',
                'details' => [
                    [
                        'name' => 'Operations Efficiency',
                        'description' => 'Achieve an operations efficiency rate of 90% or higher, indicating effective utilization of resources and optimized output from processes.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Measure the ratio of actual output to planned output over a specific period.',
                        'resources' => "Production data tracking, performance analysis tools."
                    ],
                    [
                        'name' => 'On-Time Delivery Performance',
                        'description' => 'Maintain an on-time delivery rate of 95% or higher, ensuring timely delivery of products or services to customers.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of orders or projects delivered on or before the scheduled delivery date.',
                        'resources' => "Order tracking system, delivery scheduling tools."
                    ],
                    [
                        'name' => 'Inventory Turnover Ratio',
                        'description' => 'Increase inventory turnover ratio to 8 or higher, indicating efficient management of inventory levels and reduced holding costs.',
                        'target' => '8',
                        'unit' => 'times',
                        'Methodology' => 'Calculate the ratio of cost of goods sold to average inventory value.',
                        'resources' => "Inventory management system, inventory tracking tools."
                    ],
                    [
                        'name' => 'Resource Utilization Rate',
                        'description' => 'Maintain a resource utilization rate of 80% or higher, indicating effective use of labor, equipment, and facilities.',
                        'target' => '80',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of available resources utilized for production or service delivery.',
                        'resources' => "Resource tracking system, capacity planning tools."
                    ],
                    [
                        'name' => 'Equipment Downtime',
                        'description' => 'Minimize equipment downtime to less than 5% of total operating time, ensuring maximum equipment availability and productivity.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of time that equipment is non-operational due to breakdowns or maintenance.',
                        'resources' => "Equipment monitoring system, maintenance tracking."
                    ],
                    [
                        'name' => 'Quality Assurance Rate',
                        'description' => 'Achieve a quality assurance rate of 98% or higher, indicating adherence to quality standards and customer requirements.',
                        'target' => '98',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of products or services that meet quality criteria.',
                        'resources' => "Quality control processes, inspection procedures."
                    ],
                    [
                        'name' => 'Cost per Unit Produced',
                        'description' => 'Reduce cost per unit produced by 10% to improve cost efficiency and profitability.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the total production costs divided by the number of units produced.',
                        'resources' => "Cost tracking system, cost reduction initiatives."
                    ],
                    [
                        'name' => 'Process Cycle Time',
                        'description' => 'Decrease process cycle time by 20% to streamline operations and improve throughput.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the time taken to complete a specific process or task from start to finish (Time (e.g., hours, days)).',
                        'resources' => "Process mapping tools, continuous improvement initiatives."
                    ],
                ]
            ],
            'qua' => [
                'value' => 'Quality',
                'details' => [
                    [
                        'name' => 'Defect Rate Reduction',
                        'description' => 'Decrease the defect rate by 20% to minimize product defects and non-conformities, thereby enhancing product quality and customer satisfaction.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of products or units that do not meet quality standards or customer specifications.',
                        'resources' => "Quality control measures, defect tracking system, root cause analysis techniques."
                    ],
                    [
                        'name' => 'Customer Complaint Resolution Time',
                        'description' => 'Reduce customer complaint resolution time to 24 hours to address quality issues promptly and demonstrate responsiveness to customer concerns.',
                        'target' => '24',
                        'unit' => 'hours',
                        'Methodology' => 'Measure the average time taken to investigate, address, and resolve customer complaints related to product quality or performance.',
                        'resources' => "Customer customer complaint system, customer support team, quality improvement initiatives."
                    ],
                    [
                        'name' => 'Internal Quality Audits Compliance',
                        'description' => 'Ensure 100% compliance with internal quality audit findings and corrective actions to maintain adherence to quality standards and continuous improvement.',
                        'target' => '100',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of corrective actions implemented as a result of internal quality audits.',
                        'resources' => "Internal audit program, corrective action tracking system, quality management system (QMS)."
                    ],
                    [
                        'name' => 'Scrap and Rework Costs',
                        'description' => 'Reduce scrap and rework costs by 15% to minimize waste and improve cost efficiency in production processes.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Calculate the total expenses incurred due to scrapped products or rework activities, including material, labor, and overhead costs.',
                        'resources' => "Cost accounting system, root cause analysis tools, process improvement initiatives."
                    ],
                    [
                        'name' => 'Supplier Quality Performance',
                        'description' => 'Maintain supplier quality performance at 95% or higher by evaluating supplier conformance to quality requirements and specifications.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Assess supplier performance based on criteria such as defect rates, delivery reliability, and adherence to quality standards.',
                        'resources' => "Supplier quality management system, supplier scorecard system, supplier audits."
                    ],
                    [
                        'name' => 'First Time Pass Rate (FTPR)',
                        'description' => 'Improve first time pass rate to 95% by ensuring that products meet quality standards on the initial inspection, reducing rework and production delays.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of products that pass quality inspection on the first attempt without requiring rework or additional processing.',
                        'resources' => "Quality control measures, process optimization techniques, employee training on quality standards."
                    ],
                    [
                        'name' => 'Product Non-Conformance Rate',
                        'description' => 'Reduce product non-conformance rate to less than 2% by minimizing deviations from quality specifications and standards.',
                        'target' => '2',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of products that fail to meet specified quality requirements or standards.',
                        'resources' => "Quality assurance procedures, statistical process control (SPC) tools, quality improvement initiatives."
                    ],
                    [
                        'name' => 'Quality Cost of Poor Quality (COPQ)',
                        'description' => 'Decrease the cost of poor quality by 10% to mitigate expenses related to quality failures, defects, and non-conformances.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the total expenses incurred due to quality-related issues, including scrap, rework, warranty claims, and customer returns.',
                        'resources' => "Cost accounting system, quality cost analysis tools, continuous improvement projects."
                    ],
                    [
                        'name' => 'Process Capability Improvement',
                        'description' => 'Increase process capability indices (Cpk) to 1.33 or higher for critical quality characteristics to ensure consistent product quality and process stability.',
                        'target' => '1.33',
                        'unit' => 'CPK',
                        'Methodology' => 'Calculate process capability indices to assess the ability of manufacturing processes to produce products within specified quality tolerances.',
                        'resources' => "Statistical process control (SPC) tools, process capability analysis, continuous process monitoring."
                    ],
                ]
            ],
            'ut' => [
                'value' => 'Utility',
                'details' => [
                    [
                        'name' => 'Energy Consumption Reduction',
                        'description' => 'Decrease energy consumption by 15% to improve energy efficiency and reduce utility costs while minimizing environmental impact.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Measure the total energy consumed by utilities such as electricity, natural gas, and water, and implement energy-saving initiatives.',
                        'resources' => "Energy monitoring system, energy audits, energy conservation projects."
                    ],
                    [
                        'name' => 'Water Usage Efficiency',
                        'description' => 'Improve water usage efficiency by 20% to conserve water resources and reduce utility expenses.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the total water usage across facilities and processes, and implement water-saving measures and technologies.',
                        'resources' => "Water metering systems, water recycling systems, leak detection programs."
                    ],
                    [
                        'name' => 'Waste Reduction and Recycling',
                        'description' => 'Reduce waste generation by 25% and increase recycling rates to 50% to minimize environmental impact and optimize waste management practices.',
                        'target' => '25',
                        'unit' => '%',
                        'Methodology' => 'Measure the quantity and composition of waste generated, implement waste reduction strategies, and promote recycling initiatives.',
                        'resources' => "Waste audits, waste segregation programs, recycling infrastructure."
                    ],
                    [
                        'name' => 'Utility Cost Optimization',
                        'description' => 'Reduce utility costs by 10% through strategic procurement, energy efficiency measures, and waste reduction initiatives.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Analyze utility bills and expenses, identify cost-saving opportunities, and implement measures to optimize utility expenditures.',
                        'resources' => "Utility bill analysis tools, cost-benefit analysis, utility expense tracking."
                    ],
                    [
                        'name' => 'Renewable Energy Integration',
                        'description' => 'Increase the integration of renewable energy sources to meet 20% of total energy demand, reducing reliance on non-renewable energy sources and carbon emissions.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Assess feasibility and implement renewable energy projects such as solar, wind, or biomass energy generation.',
                        'resources' => "Renewable energy assessments, renewable energy infrastructure, power purchase agreements."
                    ],
                    [
                        'name' => 'Carbon Emissions Reduction',
                        'description' => 'Decrease carbon emissions by 20% to mitigate environmental impact and contribute to climate change mitigation efforts.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure and monitor greenhouse gas emissions from utility operations, implement emission reduction measures and carbon offset initiatives. (e.g., metric tons CO2e)',
                        'resources' => "Carbon footprint assessments, emission reduction strategies, carbon offset programs."
                    ],
                    [
                        'name' => 'Utility Service Reliability',
                        'description' => 'Maintain utility service reliability at 99% uptime to ensure uninterrupted operations and minimize disruptions due to utility outages.',
                        'target' => '99',
                        'unit' => '%',
                        'Methodology' => 'Monitor utility service performance, implement preventive maintenance, and backup systems to minimize downtime.',
                        'resources' => "Utility service monitoring, maintenance schedules, contingency planning."
                    ],
                    [
                        'name' => 'Regulatory Compliance',
                        'description' => 'Ensure compliance with all relevant regulations and standards governing utility operations to avoid penalties and legal liabilities.',
                        'target' => '',
                        'unit' => '%',
                        'Methodology' => 'Stay informed about regulatory requirements, conduct audits, and implement measures to meet compliance obligations.',
                        'resources' => "Regulatory compliance tracking, compliance audits, regulatory affairs team."
                    ],
                    [
                        'name' => 'Fuel Efficiency Improvement',
                        'description' => 'Increase fuel efficiency by 5% to optimize natural gas consumption and reduce operational costs while maintaining power generation output.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Measure the amount of fuel (natural gas) consumed per unit of electricity generated (heat rate) and implement efficiency improvement measures. (Heat Rate (MMBtu/MWh))',
                        'resources' => "Performance monitoring systems, combustion optimization technologies, fuel management strategies."
                    ],
                    [
                        'name' => 'Emissions Reduction',
                        'description' => 'Decrease emissions of pollutants such as nitrogen oxides (NOx), sulfur dioxide (SO2), and particulate matter by 10% to minimize environmental impact and comply with emissions regulations.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Monitor emissions levels from power plant operations, implement emission control technologies, and optimize combustion processes. (Emissions (e.g., kg/MWh))',
                        'resources' => "Emissions monitoring equipment, pollution control systems, compliance reporting."
                    ],
                    [
                        'name' => 'Plant Availability and Reliability',
                        'description' => 'Maintain plant availability and reliability at 95% to ensure continuous power generation and meet electricity demand reliably.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Monitor equipment uptime, implement preventive maintenance programs, and address reliability issues promptly to minimize downtime.',
                        'resources' => "Condition monitoring systems, predictive maintenance technologies, outage management procedures."
                    ],
                    [
                        'name' => 'Grid Stability and Frequency Control',
                        'description' => 'Ensure grid stability and frequency control by maintaining power generation within specified frequency and voltage limits to support grid reliability and stability.',
                        'target' => '',
                        'unit' => '',
                        'Methodology' => 'Monitor grid parameters, adjust power generation output as needed, and participate in frequency regulation programs.',
                        'resources' => "Grid monitoring systems, automatic generation control (AGC) systems, grid interconnection agreements."
                    ],
                    [
                        'name' => 'Water Quality Compliance',
                        'description' => 'Achieve 100% compliance with water quality standards and regulations to ensure that treated water meets potable water quality requirements.',
                        'target' => '100',
                        'unit' => '%',
                        'Methodology' => 'Conduct regular water quality testing and monitoring, implement treatment processes to meet regulatory standards.',
                        'resources' => "Water quality testing equipment, treatment chemicals, regulatory compliance monitoring."
                    ],
                    [
                        'name' => 'Treatment Efficiency Improvement',
                        'description' => 'Increase treatment efficiency by 10% to enhance the removal of contaminants and improve overall water treatment process performance.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Analyze treatment process performance, optimize treatment parameters, and upgrade treatment equipment as needed.',
                        'resources' => "Process monitoring and control systems, treatment process optimization tools, equipment upgrades."
                    ],
                    [
                        'name' => 'Energy Efficiency in Treatment Processes',
                        'description' => 'Reduce energy consumption in treatment processes by 15% to improve operational efficiency and minimize operating costs.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Assess energy usage in treatment operations, implement energy-saving technologies, and optimize process design. (Energy consumption (e.g., kWh/m3))',
                        'resources' => "Energy monitoring systems, energy-efficient equipment, process optimization studies."
                    ],
                    [
                        'name' => 'Water Loss Reduction',
                        'description' => 'Decrease water loss through leakage and non-revenue water by 20% to conserve water resources and improve operational efficiency.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Identify and repair leaks in distribution networks, implement water loss management strategies, and improve metering accuracy.',
                        'resources' => "Leak detection technology, asset management systems, water loss monitoring programs."
                    ],
                ]
            ],
            'pm' => [
                'value' => 'Project Management',
                'details' => [
                    [
                        'name' => 'On-Time Project Completion',
                        'description' => 'Achieve a project completion rate of 95% on schedule to ensure timely delivery of project deliverables and meet stakeholder expectations.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of projects completed within the planned schedule, tracking milestones and deadlines.',
                        'resources' => "Project scheduling software, progress tracking tools, milestone monitoring."
                    ],
                    [
                        'name' => 'Cost Performance',
                        'description' => 'Maintain project costs within ±5% of the budgeted amount to prevent cost overruns and ensure financial control.',
                        'target' => '5',
                        'unit' => '$',
                        'Methodology' => 'Compare actual project expenditures against the budgeted costs, analyzing variances and implementing cost control measures.',
                        'resources' => "Cost management software, budget tracking tools, and cost estimation techniques."
                    ],
                    [
                        'name' => 'Scope Management',
                        'description' => 'Ensure that project scope changes are controlled, and scope creep is limited to 10% to prevent project delays and budget overruns.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Monitor project scope, assess change requests, and implement a formal change control process to manage scope changes effectively.',
                        'resources' => "Scope management plan, change control board, stakeholder communication channels."
                    ],
                    [
                        'name' => 'Quality of Deliverables',
                        'description' => 'Achieve a customer satisfaction rate of 90% or higher by delivering high-quality project deliverables that meet or exceed stakeholder expectations.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Measure customer satisfaction through feedback surveys, acceptance criteria, and quality assurance processes.',
                        'resources' => "Quality management plan, quality assurance procedures, customer feedback mechanisms."
                    ],
                    [
                        'name' => 'Resource Utilization',
                        'description' => 'Optimize resource utilization with an average resource utilization rate of 80% to maximize productivity and minimize resource idle time.',
                        'target' => '80',
                        'unit' => '%',
                        'Methodology' => 'Monitor resource allocation and utilization across project tasks, balancing workloads and avoiding resource bottlenecks.',
                        'resources' => "Resource management tools, resource scheduling software, capacity planning techniques."
                    ],
                    [
                        'name' => 'Enterprise Risk Effectiveness',
                        'description' => 'Reduce project risk exposure by 15% through proactive risk identification, mitigation, and contingency planning to minimize project disruptions.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => 'Identify project risks, assess their impact and probability, develop risk response strategies, and monitor risk throughout the project lifecycle.',
                        'resources' => "Risk register, risk assessment tools, risk mitigation plans."
                    ],
                    [
                        'name' => 'Stakeholder Satisfaction',
                        'description' => 'Maintain stakeholder satisfaction levels at 85% or higher by actively engaging stakeholders, managing expectations, and addressing concerns promptly.',
                        'target' => '85',
                        'unit' => '%',
                        'Methodology' => 'Solicit stakeholder feedback, communicate project progress and updates, and address stakeholder needs and preferences.',
                        'resources' => "Stakeholder engagement plan, communication plan, stakeholder feedback mechanisms."
                    ],
                    [
                        'name' => 'Lessons Learned Implementation',
                        'description' => 'Implement lessons learned from past projects with a success rate of 90% to improve project performance, processes, and outcomes continuously.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Capture project lessons learned, document best practices and improvement opportunities, and incorporate them into future project planning and execution.',
                        'resources' => "Lessons learned database, post-project reviews, continuous improvement initiatives."
                    ],
                    [
                        'name' => 'Critical Milestones',
                        'description' => 'Ensure adherence to critical project milestones, with 95% of critical milestones achieved on schedule.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Track the completion of critical project milestones against the planned schedule, addressing any delays promptly.',
                        'resources' => "Milestone tracking system, project schedule review meetings, proactive risk management."
                    ],
                    [
                        'name' => 'Technical Performance Index (TPI)',
                        'description' => 'Achieve a Technical Performance Index of 1.0 or higher, indicating that technical requirements and specifications are met as per the project plan.',
                        'target' => '1.0',
                        'unit' => 'TPI',
                        'Methodology' => 'Compare actual technical performance against planned technical requirements and specifications, ensuring conformity.',
                        'resources' => "Technical requirements documentation, performance metrics tracking."
                    ],
                    [
                        'name' => 'Earned Value Performance (EVP)',
                        'description' => 'Maintain an Earned Value Performance of 1.0 or higher, demonstrating efficient utilization of resources and progress relative to planned costs and schedule.',
                        'target' => '1.0',
                        'unit' => 'EVP',
                        'Methodology' => 'Compare earned value (EV) to planned value (PV) and actual costs (AC) to assess project performance in terms of cost and schedule.',
                        'resources' => "Earned value management system, cost and schedule tracking tools."
                    ],
                ]
            ],
            'm' => [
                'value' => 'Maintenance',
                'details' => [
                    [
                        'name' => 'Equipment Uptime',
                        'description' => 'Maintain equipment uptime at 95% or higher to ensure maximum operational availability and minimize production disruptions due to equipment breakdowns.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of time equipment is operational and available for production activities.',
                        'resources' => "Equipment monitoring systems, maintenance schedules, proactive maintenance strategies."
                    ],
                    [
                        'name' => 'Mean Time Between Failures (MTBF)',
                        'description' => 'Increase Mean Time Between Failures to 6 months, indicating improved equipment reliability and longer intervals between breakdowns.',
                        'target' => '6',
                        'unit' => 'months',
                        'Methodology' => 'Calculate the average time elapsed between equipment failures, identifying trends and implementing preventive maintenance measures.',
                        'resources' => "Maintenance records, failure analysis tools, reliability-centered maintenance (RCM) techniques."
                    ],
                    [
                        'name' => 'Planned Maintenance Compliance',
                        'description' => 'Achieve planned maintenance compliance of 90% or higher, ensuring that scheduled maintenance tasks are completed as per the maintenance plan.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Monitor the completion of planned maintenance activities compared to the scheduled maintenance plan.',
                        'resources' => "Maintenance management system, work order tracking, preventive maintenance checklists."
                    ],
                    [
                        'name' => 'Reactive Maintenance Reduction',
                        'description' => 'Reduce reactive maintenance tasks by 20% to minimize unplanned downtime and address equipment issues proactively.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Track the percentage of maintenance activities that are reactive in nature and implement strategies to shift towards proactive maintenance.',
                        'resources' => "Root cause analysis tools, predictive maintenance technologies, reliability improvement initiatives."
                    ],
                    [
                        'name' => 'Maintenance Cost per Unit of Production',
                        'description' => 'Decrease maintenance cost per unit of production by 10% to improve cost efficiency and optimize maintenance spending.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Calculate the total maintenance costs incurred divided by the total units of production output. (Currency per unit produced ($/unit))',
                        'resources' => "Maintenance cost tracking system, cost analysis tools, cost reduction strategies."
                    ],
                    [
                        'name' => 'Mean Time to Repair (MTTR)',
                        'description' => 'Reduce Mean Time to Repair to 4 hours, indicating faster response and resolution times for equipment breakdowns.',
                        'target' => '4',
                        'unit' => 'hours',
                        'Methodology' => 'Measure the average time taken to repair equipment breakdowns from the time of failure detection.',
                        'resources' => "Maintenance response protocols, spare parts availability, maintenance team efficiency."
                    ],
                    [
                        'name' => 'Asset Utilization Improvement',
                        'description' => 'Increase asset utilization rate by 15% to maximize the productivity and efficiency of equipment and facilities.',
                        'target' => '15',
                        'unit' => '%',
                        'Methodology' => '1). Assess current asset utilization rates to identify improvement areas.
2). Identify bottlenecks in asset usage and operations.
3). Implement optimization strategies such as scheduling and maintenance improvements.
4). Provide employees with training on efficient asset utilization practices.
5). Utilize asset tracking software for real-time monitoring and management.
6). Regularly monitor and analyze asset utilization data for further improvements.
7). Establish a feedback loop to gather insights from stakeholders for ongoing refinement.',
                        'resources' => "i). Asset Management Software.
ii). Lean Six Sigma methodologies for process optimization.
iii). Industry-specific best practices and case studies for asset utilization improvement.
iv). Training programs or consultants specializing in asset management and efficiency.
v). Data analytics tools for monitoring and analyzing asset utilization data."
                    ],
                    [
                        'name' => 'Spare Parts Inventory Accuracy',
                        'description' => 'Maintain spare parts inventory accuracy of 95% or higher to ensure availability of required parts for maintenance activities without overstocking.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Regularly audit spare parts inventory, reconcile records with physical inventory counts, and address discrepancies.',
                        'resources' => "Inventory management system, barcode or RFID tracking, cycle counting procedures."
                    ],
                    [
                        'name' => 'Mean Time to Failure (MTTF)',
                        'description' => 'Increase Mean Time to Failure to 12 months, indicating longer intervals between equipment failures and improved reliability.',
                        'target' => '12',
                        'unit' => 'months',
                        'Methodology' => 'Calculate the average time elapsed between equipment failures, including both scheduled and unscheduled maintenance events.',
                        'resources' => "Maintenance records, failure analysis tools, reliability improvement initiatives."
                    ],
                    [
                        'name' => 'Overall Equipment Effectiveness (OEE)',
                        'description' => 'Improve Overall Equipment Effectiveness to 90% or higher, optimizing the productivity and efficiency of production equipment.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Calculate OEE as the product of Availability, Performance, and Quality factors, identifying opportunities for improvement in each area.',
                        'resources' => "OEE tracking system, performance monitoring tools, continuous improvement initiatives."
                    ],
                    [
                        'name' => 'Energy Efficiency Improvement through Maintenance',
                        'description' => 'Achieve a 10% reduction in energy consumption attributed to maintenance activities by optimizing equipment operation and maintenance practices.',
                        'target' => '10',
                        'unit' => '%',
                        'Methodology' => 'Monitor energy consumption associated with maintenance tasks, implement energy-saving measures, and track energy efficiency improvements.',
                        'resources' => "Energy monitoring systems, maintenance process optimization, energy conservation initiatives."
                    ],

                ]
            ],
            're' => [
                'value' => 'Reliability',
                'details' => [
                    [
                        'name' => 'Mean Time Between Failures (MTBF)',
                        'description' => 'Increase MTBF to 12 months, indicating longer intervals between failures and improved reliability.',
                        'target' => '12',
                        'unit' => 'months',
                        'Methodology' => 'Calculate the average time elapsed between failures for a system or component.',
                        'resources' => "Failure data collection, reliability analysis tools."
                    ],
                    [
                        'name' => 'Failure Rate',
                        'description' => 'Reduce failure rate to 0.1 failures per operating hour, indicating fewer failures and higher reliability.',
                        'target' => '0.1',
                        'unit' => 'failures/hour',
                        'Methodology' => 'Measure the rate at which failures occur over a specific period of time.',
                        'resources' => "Failure data collection, reliability modeling."
                    ],
                    [
                        'name' => 'Availability',
                        'description' => 'Maintain equipment availability at 95% or higher, ensuring that assets are operational when needed.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Calculate the percentage of time that assets are available for use.',
                        'resources' => "Asset tracking system, downtime tracking."
                    ],
                    [
                        'name' => 'Reliability Growth',
                        'description' => 'Achieve a reliability growth rate of 5% per year, indicating continuous improvement in reliability over time.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Monitor changes in reliability metrics over successive periods to assess improvement.',
                        'resources' => "Reliability testing data, trend analysis tools."
                    ],
                    [
                        'name' => 'Mean Time to Repair (MTTR)',
                        'description' => 'Reduce MTTR to 4 hours, indicating faster restoration of services after a failure.',
                        'target' => '4',
                        'unit' => 'hours',
                        'Methodology' => 'Calculate the average time taken to repair equipment or systems after a failure.',
                        'resources' => "Maintenance tracking system, repair process optimization."
                    ],
                    [
                        'name' => 'Unplanned Downtime',
                        'description' => 'Minimize unplanned downtime to less than 5% of total operating time, reducing disruptions and improving reliability.',
                        'target' => '5',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of time that equipment is unexpectedly out of service.',
                        'resources' => "Downtime tracking system, root cause analysis."
                    ],
                    [
                        'name' => 'Reliability-Centered Maintenance (RCM) Effectiveness',
                        'description' => 'Achieve an RCM effectiveness rate of 90%, indicating that maintenance activities are effectively addressing reliability concerns.',
                        'target' => '90',
                        'unit' => '%',
                        'Methodology' => 'Assess the effectiveness of RCM strategies in improving reliability and reducing failures.',
                        'resources' => "RCM program evaluation, maintenance data analysis."
                    ],
                    [
                        'name' => 'Failure Analysis Resolution Time',
                        'description' => 'Reduce failure analysis resolution time to 24 hours, enabling prompt identification and resolution of root causes.',
                        'target' => '24',
                        'unit' => 'hours',
                        'Methodology' => 'Measure the time taken to investigate and address the root causes of failures.',
                        'resources' => "Failure analysis process, root cause analysis tools."
                    ],
                ]
            ],
            'it' => [
                'value' => 'Information Technology',
                'details' => [
                    [
                        'name' => 'System Uptime',
                        'description' => 'Maintain system uptime of 99.9% or higher, ensuring continuous availability of critical IT systems and services.',
                        'target' => '99.9',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of time that IT systems are operational and accessible to users.',
                        'resources' => "Monitoring tools, incident management processes."
                    ],
                    [
                        'name' => 'Mean Time to Resolve (MTTR)',
                        'description' => 'Reduce MTTR to 2 hours, indicating faster resolution of IT incidents and minimizing downtime.',
                        'target' => '2',
                        'unit' => 'hours',
                        'Methodology' => 'Calculate the average time taken to resolve IT incidents from the time of occurrence.',
                        'resources' => "Incident management system, escalation procedures."
                    ],
                    [
                        'name' => 'Service Desk Response Time',
                        'description' => 'Maintain a service desk response time of less than 30 minutes, ensuring timely assistance to users with IT-related issues.',
                        'target' => '30',
                        'unit' => 'minutes',
                        'Methodology' => 'Measure the time taken for the service desk to respond to user inquiries or incidents.',
                        'resources' => "Service desk ticketing system, SLA monitoring."
                    ],
                    [
                        'name' => 'Network Availability',
                        'description' => 'Achieve network availability of 99.99% or higher, ensuring reliable connectivity for users and applications.',
                        'target' => '99.99',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of time that network services are operational and accessible.',
                        'resources' => "Network monitoring tools, redundancy measures."
                    ],
                    [
                        'name' => 'Mean Time Between Failures (MTBF) for Hardware',
                        'description' => 'Increase MTBF for hardware components to 5 years, indicating improved reliability and longer intervals between failures.',
                        'target' => '5',
                        'unit' => 'years',
                        'Methodology' => 'Calculate the average time elapsed between hardware failures.',
                        'resources' => "Hardware inventory tracking, failure data analysis."
                    ],
                    [
                        'name' => 'Software Bug Fix Rate',
                        'description' => 'Maintain a software bug fix rate of 95% or higher, ensuring timely resolution of software defects and vulnerabilities.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of reported software bugs that are fixed within the target timeframe.',
                        'resources' => "Bug tracking system, software development processes."
                    ],
                    [
                        'name' => 'IT Security Incident Response Time',
                        'description' => 'Reduce IT security incident response time to 1 hour, ensuring swift detection and response to security threats.',
                        'target' => '1',
                        'unit' => 'hours',
                        'Methodology' => 'Measure the time taken to detect, investigate, and mitigate IT security incidents.',
                        'resources' => "Security incident detection tools, incident response procedures."
                    ],
                    [
                        'name' => 'Backup and Recovery Success Rate',
                        'description' => 'Achieve a backup and recovery success rate of 99%, ensuring data integrity and resilience against data loss.',
                        'target' => '99',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of backup and recovery operations that are successful.',
                        'resources' => "Backup systems, recovery testing procedures."
                    ],
                ]
            ],
            'hsse' => [
                'value' => 'HSSE',
                'details' => [
                    [
                        'name' => 'Lost Time Injury Frequency Rate (LTIFR)',
                        'description' => 'Maintain LTIFR below 1.0, indicating a low frequency of lost time injuries per million hours worked.',
                        'target' => '1.0',
                        'unit' => '',
                        'Methodology' => 'Calculate the number of lost time injuries per million hours worked by employees. (Incidents per million hours worked)',
                        'resources' => "Incident reporting system, employee hours worked data."
                    ],
                    [
                        'name' => 'Total Recordable Injury Rate (TRIR)',
                        'description' => 'Keep TRIR below 3.0, indicating a low frequency of work-related injuries requiring medical treatment or restricted work activity.',
                        'target' => '3.0',
                        'unit' => '',
                        'Methodology' => 'Calculate the number of recordable injuries per 100 full-time equivalent employees.',
                        'resources' => "Injury reporting system, employee headcount data."
                    ],
                    [
                        'name' => 'Near Miss Reporting Rate',
                        'description' => 'Increase near miss reporting rate by 20%, encouraging proactive identification and reporting of potential safety incidents.',
                        'target' => '20',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage increase in reported near misses compared to the previous period.',
                        'resources' => "Near miss reporting system, employee training on reporting procedures."
                    ],
                    [
                        'name' => 'Safety Compliance Audit Findings Closure Rate',
                        'description' => 'Achieve a closure rate of 95% for safety compliance audit findings within the designated timeframe.',
                        'target' => '95',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of safety audit findings closed within the agreed-upon timeframe.',
                        'resources' => "Audit management system, corrective action tracking."
                    ],
                    [
                        'name' => 'Environmental Incident Rate',
                        'description' => 'Maintain environmental incident rate below 0.5, indicating a low frequency of environmental incidents such as spills or releases.',
                        'target' => '0.5',
                        'unit' => 'incidents/month',
                        'Methodology' => 'Calculate the number of environmental incidents occurring within a specific timeframe.',
                        'resources' => "Environmental incident reporting system, environmental monitoring data."
                    ],
                    [
                        'name' => 'Safety Training Completion Rate',
                        'description' => 'Achieve a safety training completion rate of 100% for all employees, ensuring that all personnel receive required safety training.',
                        'target' => '100',
                        'unit' => '%',
                        'Methodology' => 'Measure the percentage of employees who have completed mandatory safety training courses.',
                        'resources' => "Training records database, training completion tracking."
                    ],
                    [
                        'name' => 'Security Incident Response Time',
                        'description' => 'Reduce security incident response time to 30 minutes, ensuring swift detection and response to security threats.',
                        'target' => '30',
                        'unit' => 'minutes',
                        'Methodology' => 'Measure the time taken to detect, investigate, and mitigate security incidents.',
                        'resources' => "Security incident detection tools, incident response procedures."
                    ],
                    [
                        'name' => 'Safety Culture Perception Survey Score',
                        'description' => 'Achieve a safety culture perception survey score of 80 or higher, indicating positive perceptions of safety culture among employees.',
                        'target' => '80',
                        'unit' => 'score',
                        'Methodology' => 'Conduct regular safety culture perception surveys to assess employee attitudes and perceptions towards safety.',
                        'resources' => "Survey tools, analysis of survey results."
                    ],
                    [
                        'name' => 'Sustainability Performance Metrics',
                        'description' => 'Achieve established environmental sustainability performance metrics, such as energy consumption reduction targets, waste reduction goals, or carbon footprint reduction goals.',
                        'target' => '',
                        'unit' => 'kWh',
                        'Methodology' => 'Track progress towards environmental sustainability objectives through data collection and analysis.',
                        'resources' => "Environmental sustainability strategy development, data monitoring and reporting systems."
                    ],
                ]
            ]
        ],
    ],
    'document_management' => [
        'default_type' => [
            'Policy',
            'Manual',
            'Procedure',
            'Work Instruction'
        ],
        'default_document_number' => [
            'policy' => '%02d',
            'manual' => '%d',
            'procedure' => '%d',
            'work_instruction' => '%03d',
        ]
    ]
];
