// resources/js/Pages/Landing.jsx
import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import '../../css/Landing.css';

export default function Landing() {
    const [formData, setFormData] = useState({
        firstName: '',
        lastName: '',
        workEmail: '',
        companyName: '',
        jobTitle: ''
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prevData => ({
            ...prevData,
            [name]: value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Here you would handle the form submission, perhaps using Inertia or an API call
        console.log('Form submitted:', formData);
        // You could redirect to a thank you page or show a success message
    };

    return (
        <>
            <Head>
                <title>Developing Strategic HRBPs: The HR Leader's Blueprint for Success</title>
                <meta name="description" content="Download your free guide to developing strategic HR Business Partners" />
            </Head>

            <div className="landing-page">
                <header>
                    <div className="logo-container">
                        <img src="/images/logo.png" alt="Company Logo" className="logo" />
                    </div>
                    <nav>
                        <ul>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Business</a></li>
                            <li><a href="#">Individuals</a></li>
                            <li><a href="#">Pricing</a></li>
                            <li><a href="#">Resources</a></li>
                        </ul>
                    </nav>
                    <div className="cta-buttons">
                        <button className="btn btn-outlined">Get a demo</button>
                        <button className="btn btn-primary">Enroll now</button>
                    </div>
                </header>

                <main>
                    <div className="content-container">
                        <div className="text-content">
                            <h1>Developing Strategic HRBPs: The HR Leader's Blueprint for Success</h1>
                            <p>
                                If your HRBP model isn't delivering results, it's time to assess your HRBP function.
                                Drill down and unleash the full potential of your HRBP function with this 9-step guide.
                            </p>
                        </div>

                        <div className="flex-container">
                            <div className="image-container">
                                <img src="/images/hrbp-guide-preview.png" alt="HRBP Guide Preview" className="guide-preview" />
                            </div>

                            <div className="form-container">
                                <h2>Get your free download now</h2>
                                <form onSubmit={handleSubmit}>
                                    <div className="form-field">
                                        <input
                                            type="text"
                                            name="firstName"
                                            placeholder="First name*"
                                            value={formData.firstName}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="form-field">
                                        <input
                                            type="text"
                                            name="lastName"
                                            placeholder="Last name*"
                                            value={formData.lastName}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="form-field">
                                        <input
                                            type="email"
                                            name="workEmail"
                                            placeholder="Work email*"
                                            value={formData.workEmail}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="form-field">
                                        <input
                                            type="text"
                                            name="companyName"
                                            placeholder="Company name*"
                                            value={formData.companyName}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="form-field">
                                        <input
                                            type="text"
                                            name="jobTitle"
                                            placeholder="Job title*"
                                            value={formData.jobTitle}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <div className="form-field">
                                        <button type="submit" className="btn btn-download">DOWNLOAD NOW</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </>
    );
}
