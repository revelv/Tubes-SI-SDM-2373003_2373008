<?php
// skill_type.php
// Database connection and other PHP logic would go here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Types</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 3px;
        }
        .header {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e2e2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .search-bar {
            display: flex;
            align-items: center;
        }
        .search-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 250px;
        }
        .search-bar button {
            background: #714B67;
            color: white;
            border: none;
            padding: 8px 15px;
            margin-left: 5px;
            border-radius: 3px;
            cursor: pointer;
        }
        .pagination {
            padding: 10px 20px;
            border-bottom: 1px solid #e2e2e2;
            font-size: 13px;
            color: #666;
        }
        .skill-categories {
            display: flex;
            flex-wrap: wrap;
            padding: 15px;
        }
        .skill-category {
            width: 48%;
            margin: 1%;
            border: 1px solid #e2e2e2;
            border-radius: 3px;
        }
        .category-header {
            background-color: #f9f9f9;
            padding: 10px 15px;
            border-bottom: 1px solid #e2e2e2;
            font-weight: 500;
        }
        .skills-list {
            padding: 10px 15px;
        }
        .skill-item {
            margin-bottom: 10px;
        }
        .skill-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        .skill-levels {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .level-tag {
            background-color: #e6f7ff;
            border: 1px solid #91d5ff;
            color: #1890ff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Skill Types</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
                <button>Search</button>
            </div>
        </div>
        
        <div class="pagination">
            1-9 / 9
        </div>
        
        <div class="skill-categories">
            <!-- Accounting and Finance -->
            <div class="skill-category">
                <div class="category-header">Accounting and Finance</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Tax Audit</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Basic Accounting</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Risk Management</div>
                        <div class="skill-levels">
                            <span class="level-tag">10+ Year Experience</span>
                            <span class="level-tag">5+ Year Experience</span>
                            <span class="level-tag">3+ Year Experience</span>
                            <span class="level-tag">1 Year Experience</span>
                            <span class="level-tag">Fresh Graduate</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Education -->
            <div class="skill-category">
                <div class="category-header">Education</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Bachelor Degree Of Informatics</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Bachelor Degree of Finance</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Bachelor Degree of Psychology or...</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Bachelor degree or Higher</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Master's degree of Finance or Higher</div>
                    </div>
                </div>
            </div>
            
            <!-- Experience -->
            <div class="skill-category">
                <div class="category-header">Experience</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Experience in Renewable Energy</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Experience in Marketing</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Experience in HR</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Experience in Production</div>
                        <div class="skill-levels">
                            <span class="level-tag">3+ Years</span>
                            <span class="level-tag">3 Years</span>
                            <span class="level-tag">2 Years</span>
                            <span class="level-tag">1 Year</span>
                            <span class="level-tag">Fresh Graduate</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- IT -->
            <div class="skill-category">
                <div class="category-header">IT</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Laravel</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">JavaScript</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">PHP</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Python</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">SQL Database</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Oracle Database</div>
                        <div class="skill-levels">
                            <span class="level-tag">10+ Year Experience</span>
                            <span class="level-tag">5+ Year Experience</span>
                            <span class="level-tag">3+ Year Experience</span>
                            <span class="level-tag">Fresh Graduate</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Languages -->
            <div class="skill-category">
                <div class="category-header">Languages</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Arabic</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Bengali</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">English</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Filipino</div>
                    </div>
                    <!-- More languages would go here -->
                </div>
            </div>
            
            <!-- Production -->
            <div class="skill-category">
                <div class="category-header">Production</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Industrial Engineering</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Machine Knowledge</div>
                        <div class="skill-levels">
                            <span class="level-tag">Pro</span>
                            <span class="level-tag">Superior</span>
                            <span class="level-tag">Intermediate</span>
                            <span class="level-tag">Beginner</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Soft Skills -->
            <div class="skill-category">
                <div class="category-header">Soft Skills</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Adaptability</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Communication</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Conflict Management</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Creativity</div>
                        <div class="skill-levels">
                            <span class="level-tag">Expert</span>
                            <span class="level-tag">Advanced</span>
                            <span class="level-tag">Intermediate</span>
                        </div>
                    </div>
                    <!-- More soft skills would go here -->
                </div>
            </div>
            
            <!-- Supply Chain -->
            <div class="skill-category">
                <div class="category-header">Supply Chain</div>
                <div class="skills-list">
                    <div class="skill-item">
                        <div class="skill-name">Supply Chain Experience</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Logistic management</div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Knowledge of Supply</div>
                        <div class="skill-levels">
                            <span class="level-tag">10+ Year Experience</span>
                            <span class="level-tag">5+ Year Experience</span>
                            <span class="level-tag">3+ Year Experience</span>
                            <span class="level-tag">Fresh Graduate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>