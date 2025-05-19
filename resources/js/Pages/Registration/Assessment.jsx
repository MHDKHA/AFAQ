import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AssessmentReportDashboard from './AssessmentResultsPage.jsx'; // Assuming AssessmentReportDashboard.jsx is in the same directory or adjust path accordingly

const AssessmentPage = ({ registration, criteria, locale = 'en' }) => {
    const [currentLocale, setCurrentLocale] = useState(locale);
    const [showReport, setShowReport] = useState(false); // State to control report visibility

    const { data, setData, post, processing, errors } = useForm({
        responses: {}, // Form responses will be stored here
    });

    // Handle changes to radio inputs and text inputs
    const handleInputChange = (criterionId, field, value) => {
        setData('responses', { // Corrected: Removed "WS"
            ...data.responses,
            [criterionId]: {
                ...(data.responses[criterionId] || {}),
                [field]: value,
            },
        });
    };

    // Toggle language
    const toggleLanguage = () => {
        setCurrentLocale(prevLocale => (prevLocale === 'en' ? 'ar' : 'en'));
    };

    // Save assessment data
    const handleSave = () => {
        post(route('frontend.assessment.save', registration.id), {
            // 'data' (which includes 'responses') is automatically sent by Inertia's post method
            onSuccess: () => {
                const notification = document.getElementById('success-notification');
                if (notification) {
                    notification.classList.remove('hidden');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000);
                }
                setShowReport(true); // Show the report dashboard
            },
            onError: (pageErrors) => {
                console.error("Error saving assessment:", pageErrors);
                // Optionally, display error notifications to the user
            }
        });
    };

    // Calculate progress
    const calculateProgress = () => {
        if (!criteria || Object.keys(criteria).length === 0) return 0;

        let totalCriteria = 0;
        let answeredCriteria = 0;

        Object.values(criteria).forEach(categoryData => {
            if (Array.isArray(categoryData)) {
                totalCriteria += categoryData.length;
                categoryData.forEach(criterion => {
                    if (
                        data.responses[criterion.id] &&
                        (data.responses[criterion.id].response !== undefined ||
                            (data.responses[criterion.id].notes && data.responses[criterion.id].notes.trim() !== ''))
                    ) {
                        answeredCriteria++;
                    }
                });
            }
        });

        if (totalCriteria === 0) return 0;
        return Math.round((answeredCriteria / totalCriteria) * 100);
    };

    const progressPercentage = calculateProgress();

    // Translations
    const translations = {
        en: {
            assessment: "Assessment",
            assessmentProgress: "Assessment Progress",
            saveAssessment: "Save Assessment",
            saving: "Saving...",
            assessmentForm: "Assessment Form",
            welcome: "Welcome",
            pleaseComplete: "Please complete the assessment below.",
            savedSuccessfully: "Assessment saved successfully!",
            yes: "Yes",
            no: "No",
            notApplicable: "Not applicable",
            addNotes: "Add notes here...",
            questionList: "Questions",
            assessmentReport: "Assessment Report",
            backToAssessment: "Back to Assessment"
        },
        ar: {
            assessment: "التقييم",
            assessmentProgress: "تقدم التقييم",
            saveAssessment: "حفظ التقييم",
            saving: "جاري الحفظ...",
            assessmentForm: "نموذج التقييم",
            welcome: "مرحباً",
            pleaseComplete: "يرجى إكمال التقييم أدناه.",
            savedSuccessfully: "تم حفظ التقييم بنجاح!",
            yes: "نعم",
            no: "لا",
            notApplicable: "لا ينطبق",
            addNotes: "أضف ملاحظات هنا...",
            questionList: "الأسئلة",
            assessmentReport: "تقرير التقييم",
            backToAssessment: "العودة للتقييم"
        }
    };

    const t = translations[currentLocale] || translations['en'];
    const isRtl = currentLocale === 'ar';

    return (
        <>
            <Head title={showReport ? t.assessmentReport : t.assessmentForm} />
            <div className={`assessment-container ${isRtl ? 'rtl' : 'ltr'}`}>
                <div className="sidebar">
                    <div className="sidebar-header">
                        <div className="logo">
                            <span className="logo-icon">A</span>
                            <span className="logo-text">{t.assessment}</span>
                        </div>
                        <button onClick={toggleLanguage} className="language-toggle">
                            {currentLocale === 'en' ? 'العربية' : 'English'}
                        </button>
                    </div>

                    <div className="user-info">
                        <div className="user-avatar">
                            {registration.name.charAt(0).toUpperCase()}
                        </div>
                        <div className="user-details">
                            <h3>{registration.name}</h3>
                            <p>{registration.company_name}</p>
                        </div>
                    </div>

                    {!showReport && (
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
                    )}

                    <div className="sidebar-footer">
                        {showReport ? (
                            <button
                                type="button"
                                onClick={() => setShowReport(false)}
                                className="save-button" // Can reuse style or create a new one
                            >
                                {t.backToAssessment}
                            </button>
                        ) : (
                            <button
                                type="button"
                                onClick={handleSave}
                                disabled={processing}
                                className="save-button"
                            >
                                {processing ? t.saving : t.saveAssessment}
                            </button>
                        )}
                    </div>
                </div>

                <div className="main-content">
                    {showReport ? (
                        <AssessmentReportDashboard
                            assessmentId={registration.id}
                            locale={currentLocale}
                        />
                    ) : (
                        <>
                            <div className="assessment-header">
                                <h1>{t.assessmentForm}</h1>
                                <p>{t.welcome}, {registration.name}! {t.pleaseComplete}</p>
                            </div>

                            <div id="success-notification" className="success-notification hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="notification-icon">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{t.savedSuccessfully}</span>
                            </div>

                            <div className="criteria-container">
                                <div className="questions-section">
                                    <h2 className="questions-title">{t.questionList}</h2>
                                    <div className="questions-list">
                                        {Object.values(criteria).flatMap((categoryData) =>
                                            Array.isArray(categoryData) ? categoryData.map((criterion, criterionIndex) => (
                                                <div key={criterion.id} className="question-card">
                                                    <div className="question-content">
                                                        <h3 className="question-text">
                                                            {/* Global question numbering would require a counter outside this map if criteria is truly flat */
                                                                Object.values(criteria).flatMap(cd => cd).findIndex(c => c.id === criterion.id) + 1
                                                            }. {currentLocale === 'ar' && criterion.question_ar ?
                                                            criterion.question_ar :
                                                            criterion.question}
                                                        </h3>
                                                        <div className="response-options">
                                                            <div className="radio-group">
                                                                {['yes', 'no', 'na'].map(optionValue => (
                                                                    <label key={optionValue} className="radio-option">
                                                                        <input
                                                                            type="radio"
                                                                            name={`criterion-${criterion.id}`}
                                                                            value={optionValue}
                                                                            checked={data.responses[criterion.id]?.response === optionValue}
                                                                            onChange={() => handleInputChange(criterion.id, 'response', optionValue)}
                                                                        />
                                                                        <span className="radio-label">
                                                                            {optionValue === 'yes' ? t.yes : optionValue === 'no' ? t.no : t.notApplicable}
                                                                        </span>
                                                                    </label>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="question-notes">
                                                        <textarea
                                                            placeholder={t.addNotes}
                                                            rows="2"
                                                            value={data.responses[criterion.id]?.notes || ''}
                                                            onChange={(e) => handleInputChange(criterion.id, 'notes', e.target.value)}
                                                            className="notes-textarea"
                                                        />
                                                    </div>
                                                </div>
                                            )) : []
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="mobile-actions">
                                <button
                                    type="button"
                                    onClick={handleSave}
                                    disabled={processing}
                                    className="mobile-save-button"
                                >
                                    {processing ? t.saving : t.saveAssessment}
                                </button>
                            </div>
                        </>
                    )}
                </div>
            </div>

            <style jsx>{`
                /* Your existing styles from AssessmentPage.jsx go here */
                /* CSS variables and all other styles remain the same */
                :root {
                  --primary: #2b6cb0;
                  --primary-light: #4299e1;
                  --primary-dark: #2c5282;
                  --accent: #f5f5dc;
                  --accent-light: #fffff0;
                  --dark: #1a1a2e;
                  --light: #ffffff;
                  --light-gray: #f8f9fc;
                  --medium-gray: #e2e8f0;
                  --text-gray: #6c757d;
                  --success: #38b2ac;
                  --shadow: 0 5px 15px rgba(0,0,0,0.08);
                  --transition: all 0.3s ease;
                }

                *, *::before, *::after {
                  box-sizing: border-box;
                  margin: 0;
                  padding: 0;
                }

                .assessment-container {
                  display: flex;
                  min-height: 100vh;
                  background-color: var(--light-gray);
                }

                .rtl {
                  direction: rtl;
                  text-align: right;
                }

                .ltr {
                  direction: ltr;
                  text-align: left;
                }

                /* Sidebar styles */
                .sidebar {
                  width: 280px;
                  background-color: var(--primary);
                  color: var(--light);
                  display: flex;
                  flex-direction: column;
                  position: fixed;
                  top: 0;
                  left: 0;
                  height: 100vh;
                  z-index: 100; /* Ensure sidebar is above dashboard content if needed */
                }

                .rtl .sidebar {
                  left: auto;
                  right: 0;
                }

                .sidebar-header {
                  padding: 1.5rem;
                  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                }

                .logo {
                  display: flex;
                  align-items: center;
                }

                .logo-icon {
                  width: 32px;
                  height: 32px;
                  background-color: var(--accent);
                  color: var(--primary);
                  border-radius: 8px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  font-weight: bold;
                  margin-right: 10px;
                }

                .rtl .logo-icon {
                  margin-right: 0;
                  margin-left: 10px;
                }

                .logo-text {
                  font-size: 1.2rem;
                  font-weight: 600;
                }

                .language-toggle {
                  background-color: var(--accent);
                  color: var(--primary);
                  border: none;
                  border-radius: 4px;
                  padding: 0.3rem 0.6rem;
                  font-size: 0.8rem;
                  cursor: pointer;
                  transition: var(--transition);
                }

                .language-toggle:hover {
                  background-color: var(--accent-light);
                }

                .user-info {
                  padding: 1.5rem;
                  display: flex;
                  align-items: center;
                  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .user-avatar {
                  width: 40px;
                  height: 40px;
                  background-color: var(--accent);
                  color: var(--primary);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  font-weight: bold;
                  margin-right: 10px;
                }

                .rtl .user-avatar {
                  margin-right: 0;
                  margin-left: 10px;
                }

                .user-details h3 {
                  font-size: 1rem;
                  font-weight: 600;
                }

                .user-details p {
                  font-size: 0.875rem;
                  opacity: 0.8;
                }

                .progress-container {
                  padding: 1.5rem;
                  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .progress-label {
                  display: flex;
                  justify-content: space-between;
                  margin-bottom: 0.5rem;
                  font-size: 0.875rem;
                }

                .progress-bar {
                  height: 8px;
                  background-color: rgba(255, 255, 255, 0.1);
                  border-radius: 4px;
                  overflow: hidden;
                }

                .progress-fill {
                  height: 100%;
                  background-color: var(--accent);
                  border-radius: 4px;
                  transition: width 0.3s ease;
                }

                .sidebar-footer {
                  padding: 1.5rem;
                  border-top: 1px solid rgba(255, 255, 255, 0.1);
                  margin-top: auto;
                }

                .save-button {
                  width: 100%;
                  padding: 0.75rem;
                  background-color: var(--accent);
                  color: var(--primary-dark);
                  border: none;
                  border-radius: 6px;
                  font-weight: 600;
                  cursor: pointer;
                  transition: var(--transition);
                }

                .save-button:hover:not(:disabled) {
                  background-color: var(--accent-light);
                }

                .save-button:disabled {
                  opacity: 0.7;
                  cursor: not-allowed;
                }

                /* Main content styles */
                .main-content {
                  flex: 1;
                  margin-left: 280px;
                  padding: 2rem;
                  /* Ensure dashboard within main-content scrolls if it's too tall */
                  overflow-y: auto;
                  height: 100vh; /* Optional: if you want main content to fill height */
                }

                .rtl .main-content {
                  margin-left: 0;
                  margin-right: 280px;
                }

                .assessment-header {
                  margin-bottom: 2rem;
                }

                .assessment-header h1 {
                  font-size: 2rem;
                  font-weight: 700;
                  color: var(--primary);
                  margin-bottom: 0.5rem;
                }

                .assessment-header p {
                  color: var(--text-gray);
                  font-size: 1.1rem;
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

                .criteria-container {
                  background-color: var(--light);
                  border-radius: 10px;
                  box-shadow: var(--shadow);
                }

                .questions-section {
                  padding: 2rem;
                }

                .questions-title {
                  font-size: 1.5rem;
                  font-weight: 600;
                  color: var(--primary);
                  margin-bottom: 1.5rem;
                  padding-bottom: 0.75rem;
                  border-bottom: 2px solid var(--light-gray);
                }

                .questions-list {
                  display: flex;
                  flex-direction: column;
                  gap: 1.5rem;
                }

                .question-card {
                  background-color: var(--light);
                  border: 1px solid var(--medium-gray);
                  border-radius: 8px;
                  padding: 1.5rem;
                  transition: var(--transition);
                }

                .question-card:hover {
                  box-shadow: var(--shadow);
                }

                .question-content {
                  margin-bottom: 1rem;
                }

                .question-text {
                  font-size: 1rem;
                  font-weight: 500;
                  color: var(--dark);
                  margin-bottom: 1rem;
                }

                .response-options {
                  margin-top: 1rem;
                }

                .radio-group {
                  display: flex;
                  gap: 1.5rem;
                }

                .radio-option {
                  display: flex;
                  align-items: center;
                  cursor: pointer;
                }

                .radio-option input {
                  margin-right: 0.5rem;
                }

                .rtl .radio-option input {
                  margin-right: 0;
                  margin-left: 0.5rem;
                }

                .radio-label {
                  font-size: 0.875rem;
                  color: var(--text-gray);
                }

                .question-notes {
                  margin-top: 1rem;
                }

                .notes-textarea {
                  width: 100%;
                  padding: 0.75rem;
                  border: 1px solid var(--medium-gray);
                  border-radius: 6px;
                  resize: vertical;
                  font-size: 0.875rem;
                  transition: var(--transition);
                }

                .notes-textarea:focus {
                  outline: none;
                  border-color: var(--primary-light);
                  box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
                }

                .mobile-actions {
                  display: none; /* Initially hidden, shown via media query */
                  position: fixed;
                  bottom: 0;
                  left: 0;
                  right: 0;
                  padding: 1rem;
                  background-color: var(--light);
                  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
                  z-index: 10;
                }

                .mobile-save-button {
                  width: 100%;
                  padding: 0.75rem;
                  background-color: var(--primary);
                  color: var(--light);
                  border: none;
                  border-radius: 6px;
                  font-weight: 600;
                  cursor: pointer;
                  transition: var(--transition);
                }

                @media (max-width: 992px) {
                  .sidebar {
                    width: 70px;
                    overflow: hidden;
                  }

                  .sidebar:hover { /* Optional: expand sidebar on hover for small screens */
                     /* width: 280px; */ /* Decide on behavior */
                  }

                  .logo-text, .user-details, .progress-label span:first-child, .language-toggle {
                    /* .progress-label span:first-child targets the text "Assessment Progress" */
                    display: none;
                  }

                  .user-info, .progress-container {
                    padding: 1rem 0; /* Adjust padding for collapsed sidebar */
                    justify-content: center;
                  }
                   .user-info .user-avatar {
                     margin-right: 0; /* Center avatar when text is hidden */
                   }
                   .rtl .user-info .user-avatar {
                     margin-left: 0;
                   }


                  .sidebar-footer {
                    /* For mobile, save button is in .mobile-actions or the button in footer handles "Back to Assessment" */
                     display: block; /* Ensure it's visible for the "Back to Assessment" button */
                  }
                  .sidebar-footer .save-button { /* Ensure text is visible or use icon */
                      font-size: 0.8rem; /* Adjust if text is too long */
                  }


                  .main-content {
                    margin-left: 70px;
                    padding: 1.5rem;
                    padding-bottom: 6rem; /* Increased padding for mobile save button */
                  }

                  .rtl .main-content {
                    margin-left: 0;
                    margin-right: 70px;
                  }

                  .mobile-actions {
                    display: ${showReport ? 'none' : 'block'}; /* Show mobile save button for form view only */
                  }

                  .radio-group {
                    flex-direction: column;
                    gap: 0.5rem;
                  }
                }

                @media (max-width: 576px) {
                  .main-content {
                    padding: 1rem;
                    padding-bottom: 6rem; /* Increased padding for mobile save button */
                  }

                  .assessment-header h1 {
                    font-size: 1.5rem;
                  }

                  .assessment-header p {
                    font-size: 1rem;
                  }

                  .questions-section {
                    padding: 1rem; /* Reduced padding for smaller screens */
                  }
                   .question-card {
                       padding: 1rem;
                   }
                }
            `}</style>
        </>
    );
};

export default AssessmentPage;
