import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';

const AssessmentPage = ({ registration, criteria, locale = 'en' }) => {
    const [formResponses, setFormResponses] = useState({});
    const [activeCategory, setActiveCategory] = useState(null);
    const [currentLocale, setCurrentLocale] = useState(locale);

    const { post, processing } = useForm({
        formResponses: {}
    });

    // Handle changes to checkbox and text inputs
    const handleInputChange = (criterionId, field, value) => {
        setFormResponses(prev => ({
            ...prev,
            [criterionId]: {
                ...prev[criterionId],
                [field]: value
            }
        }));
    };

    // Toggle language
    const toggleLanguage = () => {
        setCurrentLocale(currentLocale === 'en' ? 'ar' : 'en');
    };

    // Save assessment data
    const handleSave = () => {
        post(route('frontend.assessment.save', registration.id), {
            formResponses,
            onSuccess: () => {
                // Show success notification
                const notification = document.getElementById('success-notification');
                if (notification) {
                    notification.classList.remove('hidden');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000);
                }
            }
        });
    };

    // Set initial active category if not set
    useEffect(() => {
        if (!activeCategory && Object.keys(criteria).length > 0) {
            setActiveCategory(Object.keys(criteria)[0]);
        }
    }, [criteria, activeCategory]);

    // Calculate progress
    const calculateProgress = () => {
        if (Object.keys(criteria).length === 0) return 0;

        let totalCriteria = 0;
        let answeredCriteria = 0;

        Object.values(criteria).forEach(categoryData => {
            totalCriteria += categoryData.length;
            categoryData.forEach(criterion => {
                if (
                    formResponses[criterion.id] &&
                    (formResponses[criterion.id].is_available !== undefined ||
                        (formResponses[criterion.id].notes && formResponses[criterion.id].notes.trim() !== ''))
                ) {
                    answeredCriteria++;
                }
            });
        });

        return Math.round((answeredCriteria / totalCriteria) * 100);
    };

    const progressPercentage = calculateProgress();

    // Translations
    const translations = {
        en: {
            assessment: "Assessment",
            assessmentProgress: "Assessment Progress",
            saveAssessment: "Complete Assessment",
            saving: "Processing...",
            assessmentForm: "Assessment Form",
            welcome: "Welcome",
            pleaseComplete: "Please complete the assessment criteria below.",
            savedSuccessfully: "Assessment saved successfully!",
            available: "Available",
            addNotes: "Add notes here...",
        },
        ar: {
            assessment: "التقييم",
            assessmentProgress: "تقدم التقييم",
            saveAssessment: "إكمال التقييم",
            saving: "جاري المعالجة...",
            assessmentForm: "نموذج التقييم",
            welcome: "مرحباً",
            pleaseComplete: "يرجى إكمال معايير التقييم أدناه.",
            savedSuccessfully: "تم حفظ التقييم بنجاح!",
            available: "متوفر",
            addNotes: "أضف ملاحظات هنا...",
        }
    };

    const t = translations[currentLocale];
    const isRtl = currentLocale === 'ar';

    return (
        <>
            <Head title={t.assessmentForm} />
            <div className={`container ${isRtl ? 'rtl' : 'ltr'}`}>
                <div className="visual-section">
                    <div className="decoration dec-1"></div>
                    <div className="decoration dec-2"></div>

                    <div className="visual-content">
                        <div className="ebook-preview">
                            <div className="ebook-cover">
                                <img src="/images/assessment-visual.jpg" alt="Assessment Visual" />
                            </div>
                        </div>

                        <h2>{t.assessment}</h2>
                        <p>{t.welcome}, {registration.name}</p>

                        <div className="progress-container">
                            <div className="progress-label">
                                <span>{t.assessmentProgress}</span>
                                <span>{progressPercentage}%</span>
                            </div>
                            <div className="progress-bar">
                                <div
                                    className="progress-fill"
                                    style={{ width: `${progressPercentage}%` }}
                                ></div>
                            </div>
                        </div>

                        <div className="highlights">
                            <div className="highlight-item">
                                <div className="highlight-icon">✓</div>
                                <div className="highlight-text">{registration.company_name}</div>
                            </div>
                            <div className="language-toggle" onClick={toggleLanguage}>
                                {currentLocale === 'en' ? 'العربية' : 'English'}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="form-section">
                    <div className="form-header">
                        <h2>{t.assessmentForm}</h2>
                        <p>{t.pleaseComplete}</p>
                    </div>

                    <div id="success-notification" className="success-notification hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="notification-icon">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{t.savedSuccessfully}</span>
                    </div>

                    <div className="categories-nav">
                        <ul>
                            {Object.entries(criteria).map(([categoryName, categoryData]) => (
                                <li
                                    key={categoryName}
                                    className={`category-item ${activeCategory === categoryName ? 'active' : ''}`}
                                    onClick={() => setActiveCategory(categoryName)}
                                >
                                    <span className="category-name">{categoryName}</span>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="criteria-container">
                        {activeCategory && (
                            <div className="category-section">
                                <div className="form-group-header">
                                    <h3>{activeCategory}</h3>
                                </div>
                                <div className="criteria-list">
                                    {criteria[activeCategory].map((criterion) => (
                                        <div key={criterion.id} className="criterion-card">
                                            <div className="criterion-header">
                                                <h4 className="criterion-question">
                                                    {currentLocale === 'ar' && criterion.question_ar ?
                                                        criterion.question_ar :
                                                        criterion.question}
                                                </h4>
                                                <label className="availability-toggle">
                                                    <input
                                                        type="checkbox"
                                                        checked={formResponses[criterion.id]?.is_available || false}
                                                        onChange={(e) => handleInputChange(criterion.id, 'is_available', e.target.checked)}
                                                    />
                                                    <span className="toggle-label">{t.available}</span>
                                                </label>
                                            </div>
                                            <div className="form-group">
                                                <textarea
                                                    placeholder={t.addNotes}
                                                    rows="2"
                                                    value={formResponses[criterion.id]?.notes || ''}
                                                    onChange={(e) => handleInputChange(criterion.id, 'notes', e.target.value)}
                                                    className="form-control"
                                                />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    <button
                        type="button"
                        onClick={handleSave}
                        disabled={processing}
                        className="btn-primary"
                    >
                        {processing ? t.saving : t.saveAssessment}
                    </button>
                </div>
            </div>

            <style jsx>{`
                :root {
                    --primary: #4a2a81;
                    --primary-light: #6a3fb8;
                    --accent: #ff9500;
                    --dark: #1a1a2e;
                    --light: #ffffff;
                    --background: #f8f9fc;
                    --gray-light: #f1f2f6;
                    --success: #38b2ac;
                    --shadow: 0 10px 30px rgba(0,0,0,0.08);
                    --transition: all 0.3s ease;
                }

                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                body {
                    background-color: var(--background);
                    color: var(--dark);
                    line-height: 1.6;
                    overflow-x: hidden;
                }

                .container {
                    display: flex;
                    min-height: 100vh;
                    width: 100%;
                }

                .rtl {
                    direction: rtl;
                    text-align: right;
                }

                .ltr {
                    direction: ltr;
                    text-align: left;
                }

                .visual-section {
                    flex: 0 0 45%;
                    background-color: var(--primary);
                    color: var(--light);
                    position: relative;
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    padding: 3rem;
                }

                .visual-content {
                    position: relative;
                    z-index: 2;
                    max-width: 550px;
                }

                .ebook-preview {
                    perspective: 1000px;
                    margin-bottom: 2rem;
                }

                .ebook-cover {
                    position: relative;
                    transform: rotateY(15deg) rotateX(5deg);
                    transition: var(--transition);
                    box-shadow: var(--shadow);
                    max-width: 90%;
                    border-radius: 8px;
                }

                .ebook-cover:hover {
                    transform: rotateY(5deg) rotateX(0deg);
                }

                .ebook-cover img {
                    max-width: 100%;
                    border-radius: 8px;
                    display: block;
                }

                .form-section {
                    flex: 0 0 55%;
                    padding: 2rem 6rem;
                    display: flex;
                    flex-direction: column;
                    overflow-y: auto;
                }

                .form-header {
                    margin-bottom: 2rem;
                }

                .form-header h2 {
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: var(--primary);
                    margin-bottom: 1rem;
                }

                .form-header p {
                    color: #6c757d;
                    font-size: 1.1rem;
                }

                .form-group-header {
                    margin: 1.5rem 0 1rem;
                }

                .form-group-header h3 {
                    color: var(--primary);
                    font-size: 1.3rem;
                    font-weight: 600;
                }

                .form-group {
                    margin-bottom: 1.5rem;
                }

                .form-control {
                    width: 100%;
                    padding: 1rem 1.2rem;
                    font-size: 1rem;
                    line-height: 1.5;
                    color: var(--dark);
                    background-color: var(--light);
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    transition: var(--transition);
                }

                .form-control:focus {
                    border-color: var(--primary-light);
                    outline: 0;
                    box-shadow: 0 0 0 3px rgba(74, 42, 129, 0.1);
                }

                .btn-primary {
                    display: block;
                    width: 100%;
                    padding: 1rem;
                    font-size: 1.1rem;
                    font-weight: 600;
                    text-align: center;
                    color: var(--light);
                    background-color: var(--accent);
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: var(--transition);
                    margin-top: 2rem;
                }

                .btn-primary:hover:not(:disabled) {
                    background-color: #e68600;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 15px rgba(255, 149, 0, 0.2);
                }

                .btn-primary:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                }

                .decoration {
                    position: absolute;
                    opacity: 0.1;
                    z-index: 1;
                }

                .dec-1 {
                    width: 500px;
                    height: 500px;
                    border-radius: 50%;
                    background: linear-gradient(45deg, var(--accent), #ff5e62);
                    top: -200px;
                    right: -200px;
                }

                .dec-2 {
                    width: 300px;
                    height: 300px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #4facfe, #00f2fe);
                    bottom: -100px;
                    left: -100px;
                }

                .highlights {
                    margin-top: 1.5rem;
                }

                .highlight-item {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1rem;
                }

                .highlight-icon {
                    margin-right: 1rem;
                    width: 24px;
                    height: 24px;
                    background-color: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                }

                .rtl .highlight-icon {
                    margin-right: 0;
                    margin-left: 1rem;
                }

                .highlight-text {
                    font-size: 1rem;
                }

                .language-toggle {
                    display: inline-block;
                    background-color: rgba(255, 255, 255, 0.2);
                    color: var(--light);
                    padding: 0.5rem 1rem;
                    border-radius: 5px;
                    margin-top: 1rem;
                    cursor: pointer;
                    transition: var(--transition);
                }

                .language-toggle:hover {
                    background-color: rgba(255, 255, 255, 0.3);
                }

                .progress-container {
                    margin-top: 1.5rem;
                }

                .progress-label {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 0.5rem;
                    font-size: 0.875rem;
                }

                .progress-bar {
                    height: 8px;
                    background-color: rgba(255, 255, 255, 0.2);
                    border-radius: 4px;
                    overflow: hidden;
                }

                .progress-fill {
                    height: 100%;
                    background-color: var(--accent);
                    border-radius: 4px;
                    transition: width 0.3s ease;
                }

                .success-notification {
                    display: flex;
                    align-items: center;
                    padding: 1rem;
                    background-color: var(--success);
                    color: var(--light);
                    border-radius: 6px;
                    margin-bottom: 1.5rem;
                    animation: slideIn 0.3s ease-out;
                }

                .hidden {
                    display: none;
                }

                @keyframes slideIn {
                    from {
                        transform: translateY(-20px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .notification-icon {
                    width: 20px;
                    height: 20px;
                    margin-right: 0.5rem;
                }

                .rtl .notification-icon {
                    margin-right: 0;
                    margin-left: 0.5rem;
                }

                .categories-nav {
                    margin-bottom: 1.5rem;
                }

                .categories-nav ul {
                    display: flex;
                    list-style: none;
                    gap: 0.5rem;
                    overflow-x: auto;
                    padding-bottom: 0.5rem;
                }

                .category-item {
                    padding: 0.75rem 1.2rem;
                    background-color: var(--gray-light);
                    border-radius: 8px;
                    cursor: pointer;
                    transition: var(--transition);
                    white-space: nowrap;
                }

                .category-item:hover {
                    background-color: #e5e7eb;
                }

                .category-item.active {
                    background-color: var(--primary);
                    color: var(--light);
                }

                .criteria-container {
                    background-color: var(--light);
                    border-radius: 10px;
                    box-shadow: var(--shadow);
                    padding: 1.5rem;
                    margin-bottom: 1.5rem;
                }

                .criterion-card {
                    margin-bottom: 1.5rem;
                    padding-bottom: 1.5rem;
                    border-bottom: 1px solid var(--gray-light);
                }

                .criterion-card:last-child {
                    margin-bottom: 0;
                    padding-bottom: 0;
                    border-bottom: none;
                }

                .criterion-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 1rem;
                }

                .criterion-question {
                    font-size: 1rem;
                    font-weight: 500;
                    margin-right: 1rem;
                }

                .rtl .criterion-question {
                    margin-right: 0;
                    margin-left: 1rem;
                }

                .availability-toggle {
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    white-space: nowrap;
                }

                .availability-toggle input {
                    margin-right: 0.5rem;
                }

                .rtl .availability-toggle input {
                    margin-right: 0;
                    margin-left: 0.5rem;
                }

                @media (max-width: 1200px) {
                    .form-section {
                        padding: 2rem 3rem;
                    }
                }

                @media (max-width: 992px) {
                    .container {
                        flex-direction: column;
                    }

                    .visual-section, .form-section {
                        flex: 0 0 100%;
                    }

                    .visual-section {
                        padding: 3rem 2rem;
                        justify-content: center;
                    }

                    .visual-content {
                        max-width: 100%;
                        text-align: center;
                        margin: 0 auto;
                    }

                    .ebook-preview {
                        margin-bottom: 1.5rem;
                    }

                    .ebook-cover {
                        margin: 0 auto;
                    }

                    .form-section {
                        padding: 2rem;
                    }
                }

                @media (max-width: 768px) {
                    .criterion-header {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .criterion-question {
                        margin-right: 0;
                        margin-bottom: 0.5rem;
                    }

                    .rtl .criterion-question {
                        margin-left: 0;
                    }

                    .availability-toggle {
                        margin-bottom: 0.5rem;
                    }
                }

                @media (max-width: 576px) {
                    .form-section {
                        padding: 1.5rem;
                    }

                    .form-header h2 {
                        font-size: 2rem;
                    }
                }
            `}</style>
        </>
    );
};

export default AssessmentPage;
