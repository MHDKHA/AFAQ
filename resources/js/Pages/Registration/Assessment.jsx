import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';

const AssessmentPage = ({ registration, criteria, locale = 'en' }) => {
    const [formResponses, setFormResponses] = useState({});
    const [activeCategory, setActiveCategory] = useState(null);
    const [currentLocale, setCurrentLocale] = useState(locale);

    const { post, processing } = useForm({
        formResponses: {}
    });

    // Handle changes to radio inputs and text inputs
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
                    (formResponses[criterion.id].response !== undefined ||
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
            questionList: "Questions"
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
            questionList: "الأسئلة"
        }
    };

    const t = translations[currentLocale];
    const isRtl = currentLocale === 'ar';

    return (
        <>
            <Head title={t.assessmentForm} />
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

                    {/* Removed category navigation */}
                    <div className="sidebar-footer">
                        <button
                            type="button"
                            onClick={handleSave}
                            disabled={processing}
                            className="save-button"
                        >
                            {processing ? t.saving : t.saveAssessment}
                        </button>
                    </div>
                </div>

                <div className="main-content">
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
                                {/* Flatten all criteria across categories */}
                                {Object.values(criteria).flatMap((categoryData, categoryIndex) =>
                                    categoryData.map((criterion, criterionIndex) => (
                                        <div key={criterion.id} className="question-card">
                                            <div className="question-content">
                                                <h3 className="question-text">
                                                    {criterionIndex + 1}. {currentLocale === 'ar' && criterion.question_ar ?
                                                    criterion.question_ar :
                                                    criterion.question}
                                                </h3>
                                                <div className="response-options">
                                                    <div className="radio-group">
                                                        <label className="radio-option">
                                                            <input
                                                                type="radio"
                                                                name={`criterion-${criterion.id}`}
                                                                value="yes"
                                                                checked={formResponses[criterion.id]?.response === 'yes'}
                                                                onChange={() => handleInputChange(criterion.id, 'response', 'yes')}
                                                            />
                                                            <span className="radio-label">{t.yes}</span>
                                                        </label>
                                                        <label className="radio-option">
                                                            <input
                                                                type="radio"
                                                                name={`criterion-${criterion.id}`}
                                                                value="no"
                                                                checked={formResponses[criterion.id]?.response === 'no'}
                                                                onChange={() => handleInputChange(criterion.id, 'response', 'no')}
                                                            />
                                                            <span className="radio-label">{t.no}</span>
                                                        </label>
                                                        <label className="radio-option">
                                                            <input
                                                                type="radio"
                                                                name={`criterion-${criterion.id}`}
                                                                value="na"
                                                                checked={formResponses[criterion.id]?.response === 'na'}
                                                                onChange={() => handleInputChange(criterion.id, 'response', 'na')}
                                                            />
                                                            <span className="radio-label">{t.notApplicable}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="question-notes">
                                                <textarea
                                                    placeholder={t.addNotes}
                                                    rows="2"
                                                    value={formResponses[criterion.id]?.notes || ''}
                                                    onChange={(e) => handleInputChange(criterion.id, 'notes', e.target.value)}
                                                    className="notes-textarea"
                                                />
                                            </div>
                                        </div>
                                    ))
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
                </div>
            </div>

            <style jsx>{`
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
          display: none;
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

          .logo-text, .user-details, .progress-label, .language-toggle {
            display: none;
          }

          .user-info, .progress-container {
            padding: 1rem 0;
            justify-content: center;
          }

          .sidebar-footer {
            display: none;
          }

          .main-content {
            margin-left: 70px;
            padding: 1.5rem;
            padding-bottom: 5rem;
          }

          .rtl .main-content {
            margin-left: 0;
            margin-right: 70px;
          }

          .mobile-actions {
            display: block;
          }

          .radio-group {
            flex-direction: column;
            gap: 0.5rem;
          }
        }

        @media (max-width: 576px) {
          .main-content {
            padding: 1rem;
            padding-bottom: 5rem;
          }

          .assessment-header h1 {
            font-size: 1.5rem;
          }

          .assessment-header p {
            font-size: 1rem;
          }

          .questions-section {
            padding: 1.5rem;
          }
        }
      `}</style>
        </>
    );
};

export default AssessmentPage;
