<?php
/**
 * Japanese (ja) Translation File
 * Viewfinder Upstream - Digital Sovereignty Readiness Assessment
 */

return [
    // ========================================
    // 共通要素
    // ========================================
    'common.home' => 'ホーム',
    'common.back' => '戻る',
    'common.yes' => 'はい',
    'common.no' => 'いいえ',
    'common.dont_know' => '不明',
    'common.next' => '次へ',
    'common.previous' => '前へ',
    'common.submit' => '送信',
    'common.cancel' => 'キャンセル',
    'common.close' => '閉じる',
    'common.save' => '保存',
    'common.reset' => 'リセット',
    'common.loading' => '読み込み中...',

    // ========================================
    // ランディングページ
    // ========================================
    'landing.title' => 'デジタル主権ナビゲーター',
    'landing.assessment_title' => 'デジタル主権準備評価',
    'landing.assessment_description' => '7つの主要ドメインにおける組織のデジタル主権準備状況を評価する10〜15分の簡易評価',
    'landing.select_profile' => '業界/コンテキストを選択:',
    'landing.start_assessment' => '評価を開始',
    'landing.domain_weighting' => 'ドメインの重み付け',
    'landing.cmmi_levels' => 'CMMI成熟度レベル',
    'landing.customize_weights' => 'ドメインの重み付けをカスタマイズ',
    'landing.adjust_weights_hint' => '重み付けを1.0×（標準）から2.0×（最重要）まで調整',

    // ========================================
    // プロファイル名
    // ========================================
    'profile.balanced.name' => 'バランス',
    'profile.balanced.description' => 'すべてのドメインに均等な重み付け - 一般的な評価や特定の規制要件のない組織に適しています。',

    'profile.financial.name' => '金融サービス',
    'profile.financial.description' => '銀行および金融業界のデータ保護、監査管理、コンプライアンスを重視（PCI DSS、データ居住地、マネーロンダリング対策）。',

    'profile.healthcare.name' => '医療',
    'profile.healthcare.description' => '患者データ保護（HIPAA、GDPR）と24時間365日稼働が必要な生命維持システムの運用レジリエンスに重点を置いています。',

    'profile.government.name' => '政府・公共部門',
    'profile.government.description' => '機密性の高い市民データや重要な国家インフラを扱う公共部門組織のための包括的な主権（NIS2、FedRAMP）。',

    'profile.technology.name' => 'テクノロジー＆SaaS',
    'profile.technology.description' => 'ベンダーロックインを回避し、競争力を維持するため、技術的独立性、オープンソース戦略、マルチクラウドポータビリティを優先します。',

    'profile.manufacturing.name' => '製造・産業',
    'profile.manufacturing.description' => '継続的な運用と産業制御システムにおける知的財産保護のため、運用レジリエンス、生産稼働時間、OT/IT統合を重視します。',

    'profile.telecommunications.name' => '通信',
    'profile.telecommunications.description' => '重要インフラ保護、加入者データ主権、24時間365日のサービス可用性に重点を置いています（NIS2、ネットワークセキュリティ）。',

    'profile.energy.name' => 'エネルギー・公益事業',
    'profile.energy.description' => '重要インフラ保護、電力網の信頼性、基幹サービスのためのSCADAシステムセキュリティを優先します（NIS2、NERC CIP）。',

    'profile.custom.name' => 'カスタム',
    'profile.custom.description' => '特定の規制要件、ビジネスモデル、組織の優先事項に基づいて、独自のドメイン重み付けを定義します。',

    // ========================================
    // ドメイン名
    // ========================================
    'domain.data_sovereignty' => 'データ主権',
    'domain.technical_sovereignty' => '技術主権',
    'domain.operational_sovereignty' => '運用主権',
    'domain.assurance_sovereignty' => '保証主権',
    'domain.open_source' => 'オープンソース',
    'domain.executive_oversight' => '経営監督',
    'domain.managed_services' => 'マネージドサービス',

    // ========================================
    // ドメインの説明
    // ========================================
    'domain.data_sovereignty.description' => 'データの管理、居住地、および暗号化の主権',
    'domain.technical_sovereignty.description' => '技術的独立性とプラットフォームの移植性',
    'domain.operational_sovereignty.description' => '運用の独立性とレジリエンス',
    'domain.assurance_sovereignty.description' => 'セキュリティ、コンプライアンス、監査管理',
    'domain.open_source.description' => 'オープンソース戦略と独立性',
    'domain.executive_oversight.description' => '戦略的ガバナンスとリーダーシップのコミットメント',
    'domain.managed_services.description' => 'クラウドサービスの管理とプロバイダーの独立性',

    // ========================================
    // 成熟度レベル
    // ========================================
    'maturity.initial' => '初期',
    'maturity.managed' => '管理',
    'maturity.defined' => '定義',
    'maturity.quantitative' => '定量的管理',
    'maturity.optimizing' => '最適化',

    // 成熟度レベルの説明（短縮版）
    'maturity.initial.short' => '予測不可能で、管理が不十分で、反応的なプロセス',
    'maturity.managed.short' => 'ポリシーに従って計画・実行されるプロジェクト、基本的な管理体制',
    'maturity.defined.short' => '組織全体で標準化、文書化、プロアクティブなプロセス',
    'maturity.quantitative.short' => '統計的手法とデータを用いて測定・管理',
    'maturity.optimizing.short' => '継続的改善とイノベーションに焦点を当てたプロセス',

    // 成熟度レベルの説明（詳細版）
    'maturity.initial.description' => 'プロセスは予測不可能で、管理が不十分で、反応的です。貴組織のデジタル主権の実践は場当たり的で、外部プロバイダーへの依存度が高い状態です。成功は実証されたプロセスではなく、個人の奮闘に依存しています。',
    'maturity.managed.description' => 'プロジェクトはポリシーに従って計画・実行されます。貴組織はプロジェクトレベルでデジタル主権要件を管理していますが、プロセスが組織全体で再現可能ではない可能性があります。基本的な管理体制は整っていますが、まだ標準化されていません。',
    'maturity.defined.description' => 'プロセスは十分に特徴付けられ、理解され、プロアクティブです。貴組織はすべてのドメインにわたってデジタル主権プロセスを文書化し標準化しています。実践は一貫性があり再現可能で、明確なガバナンス構造が整っています。',
    'maturity.quantitative.description' => 'プロセスは定量的データを用いて測定・管理されます。貴組織は統計的・分析的手法を用いてデジタル主権を管理し、品質とパフォーマンスの定量的目標を設定しています。プロセスパフォーマンスの変動は理解され、管理されています。',
    'maturity.optimizing.description' => '継続的改善とイノベーションに焦点を当てています。貴組織は定量的理解に基づいてデジタル主権プロセスを継続的に改善しています。革新的な実践を積極的に特定・展開し、業界をリードする主権態勢を維持しています。',

    // 成熟度レベルの範囲
    'maturity.initial.range' => '0-20%',
    'maturity.managed.range' => '21-40%',
    'maturity.defined.range' => '41-60%',
    'maturity.quantitative.range' => '61-80%',
    'maturity.optimizing.range' => '81-100%',

    // ========================================
    // 評価ページ
    // ========================================
    'assessment.title' => 'デジタル主権準備評価',
    'assessment.subtitle' => 'デジタル主権準備状況を評価する10〜15分の簡易評価',
    'assessment.profile' => 'プロファイル:',
    'assessment.about_title' => 'このツールについて',
    'assessment.about_description' => 'この軽量評価ツールは、組織のデジタル主権準備状況を評価するのに役立ちます。現在の実践と要件に基づいて、以下の質問に答えてください。',
    'assessment.time_required' => '所要時間:',
    'assessment.time_value' => '10〜15分',
    'assessment.questions_count' => '質問数:',
    'assessment.questions_value' => '7ドメインにわたる21問（はい/いいえ/不明）',
    'assessment.output' => '出力:',
    'assessment.output_value' => '推奨される次のステップを含む準備スコア',
    'assessment.dont_know_hint' => '不明な場合は？',
    'assessment.dont_know_explanation' => '「不明」とマークした質問は「調査すべき質問」として表示されます',

    // 評価ボタン
    'assessment.button.next' => '次へ',
    'assessment.button.previous' => '前へ',
    'assessment.button.complete' => '評価を完了',
    'assessment.button.generate_report' => '評価レポートを生成',
    'assessment.button.reset' => 'すべての回答をリセット',
    'assessment.button.new' => '新規評価',

    // ========================================
    // 質問（データ主権）
    // ========================================
    'question.ds1.text' => '貴組織は現在、貴国/地域/業界に関連するすべてのデータ居住要件または規制に準拠していますか？',
    'question.ds1.tooltip' => '例：GDPR（EU）、PIPEDA（カナダ）、LGPD（ブラジル）、特定の管轄区域内にデータを保持することを要求する業界規制。',

    'question.ds2.text' => '暗号化キーを独占的に管理していますか（クラウドプロバイダーと共有していない）？',
    'question.ds2.tooltip' => 'BYOK（Bring Your Own Key）またはHYOK（Hold Your Own Key）により、クラウドプロバイダーではなく、貴社のみがデータを復号化できることが保証されます。',

    'question.ds3.text' => '機密データが特定の地理的境界を越えることを防止できますか？',
    'question.ds3.tooltip' => '真のクラウドポータビリティとは、大規模な書き換えなしに、ワークロードをプロバイダー間（AWS、Azure、ローカルプロバイダー、オンプレミスなど）で移動できることを意味します。',

    // ========================================
    // 質問（技術主権）
    // ========================================
    'question.ts1.text' => '現在の技術スタックでベンダーロックインのリスクを軽減できますか？',
    'question.ts1.tooltip' => 'ベンダーロックインは、独自技術によりプロバイダーの切り替えが困難または高コストになる場合に発生します。オープンソースと標準ベースのプラットフォームはこのリスクを軽減します。',

    'question.ts2.text' => 'プラットフォームでは独自APIよりもオープンスタンダードを優先していますか？',
    'question.ts2.tooltip' => 'オープンスタンダード（Kubernetes、OCIコンテナ、POSIX）は移植性と相互運用性を保証します。独自APIは特定のベンダーへの依存を生み出します。',

    'question.ts3.text' => '必要に応じて、重要なアプリケーションを異なるクラウドプラットフォームに移行できますか？',
    'question.ts3.tooltip' => '真のクラウドポータビリティとは、大規模な書き換えなしに、ワークロードをプロバイダー間（AWS、Azure、オンプレミス）で移動できることを意味します。',

    // ========================================
    // 質問（運用主権）
    // ========================================
    'question.os1.text' => '外部クラウドサービスが利用できなくなった場合でも、重要なシステムの運用を継続できますか？',
    'question.os1.tooltip' => '運用レジリエンスとは、クラウドプロバイダーに障害やサービス中断が発生した場合でも、重要なシステムを独立して実行できることを意味します。',

    'question.os2.text' => '主権インフラを管理するための社内技術専門知識を持っていますか？',
    'question.os2.tooltip' => '主権システムの管理には、セキュリティ、コンプライアンス、インフラ管理における専門的スキルが必要です。',

    'question.os3.text' => '地政学的シナリオを考慮した災害復旧計画を持っていますか？',
    'question.os3.tooltip' => '地政学的リスクには、制裁、貿易制限、データアクセス法（CLOUD Actなど）が含まれます。DR計画では、国際的なプロバイダーが制限される可能性のあるシナリオに対処する必要があります。',

    // ========================================
    // 質問（保証主権）
    // ========================================
    'question.as1.text' => 'デジタルシステム、データ、インフラのセキュリティ、整合性、信頼性を独立して検証する能力を持っていますか？',
    'question.as1.tooltip' => 'システムのセキュリティを独立して検証することは、データの完全な管理を確保し、運用の独立性を維持し、監査可能で回復力のあるインフラを通じて信頼を構築するために、主権にとって不可欠です。',

    'question.as2.text' => 'セキュリティログと監査証跡の保存場所を管理していますか？',
    'question.as2.tooltip' => 'セキュリティログには機密情報が含まれており、保持と場所の要件を満たす必要があります。同じベンダーにログを保存すると、単一障害点が生じます。',

    'question.as3.text' => '貴国の該当するデジタル主権関連基準を認識していますか？',
    'question.as3.tooltip' => 'デジタル主権に関連するグローバル規制は進化中で大きく異なりますが、一般的には国境内のデータと技術に対する国家の管理に焦点を当てています。これらの規則は、国家安全保障、経済的利益、市民のプライバシー保護によって動機付けられており、企業の国際的な事業運営に大きな影響を与える可能性があります。',

    // ========================================
    // 質問（オープンソース）
    // ========================================
    'question.oss1.text' => '独自ソフトウェアよりもオープンソースソフトウェアを優先する正式なポリシーを持っていますか？',
    'question.oss1.tooltip' => '多くの政府や規制対象組織は、透明性と主権のためにオープンソースを義務付けています。正式なポリシーは調達決定を推進します。',

    'question.oss2.text' => '必要に応じて、重要なオープンソース依存関係をフォークして独立して保守できますか？',
    'question.oss2.tooltip' => '真のソフトウェア主権とは、上流プロジェクトが方向を変えたり利用できなくなったりした場合に、所有権を取得できる能力を意味します。',

    'question.oss3.text' => '業務にとって重要な戦略的オープンソースプロジェクトに積極的に貢献していますか？',
    'question.oss3.tooltip' => 'OSSコミュニティに貢献することで、プロジェクトの方向性に影響を与え、社内の専門知識を構築できます。',

    // ========================================
    // 質問（経営監督）
    // ========================================
    'question.eo1.text' => 'デジタル主権イニシアチブのためのエグゼクティブスポンサーまたは運営委員会を持っていますか？',
    'question.eo1.tooltip' => 'エグゼクティブスポンサーシップは、デジタル主権イニシアチブの資金調達、優先順位付け、組織横断的な調整を保証します。',

    'question.eo2.text' => 'デジタル主権は、企業戦略またはIT戦略の明示的な一部ですか？',
    'question.eo2.tooltip' => 'デジタル主権への戦略的コミットメントは、技術選択、ベンダー選定、アーキテクチャ決定を推進します。',

    'question.eo3.text' => '主権イニシアチブとコンプライアンスのために割り当てられた専用予算を持っていますか？',
    'question.eo3.tooltip' => '予算配分は真剣さを示し、デジタル主権プログラムの実行を可能にします。',

    // ========================================
    // 質問（マネージドサービス）
    // ========================================
    'question.ms1.text' => 'クラウド展開を特定の地域または認定されたデータセンターに制限できますか？',
    'question.ms1.tooltip' => '地域制限により、データ居住法への準拠が保証され、地政学的リスクが軽減されます。',

    'question.ms2.text' => 'クラウドプロバイダーのシステムへの管理アクセスを管理・監視していますか？',
    'question.ms2.tooltip' => '特権アクセス管理により、許可された担当者のみがシステムにアクセスできることが保証されます。',

    'question.ms3.text' => 'ワークロードを異なるクラウドプロバイダーに移行する能力をテストまたは検証しましたか？',
    'question.ms3.tooltip' => '定期的な移行テストにより、ポータビリティが単なる理論ではないことが証明されます。',

    // ========================================
    // 結果ページ
    // ========================================
    'results.title' => 'デジタル主権準備評価結果',
    'results.assessment_date' => '評価日:',
    'results.profile' => 'プロファイル:',
    'results.maturity_level' => '成熟度レベル',
    'results.score' => 'スコア',
    'results.of_points' => '{max}点中',
    'results.raw_score' => '生スコア: {score}点',

    // 結果セクション
    'results.domain_analysis' => 'ドメイン分析',
    'results.domain_analysis.intro' => '7つのデジタル主権ドメインにわたる準備状況の内訳:',
    'results.domain_analysis.weights_note' => '重み付けは、{profile}プロファイルにおける各ドメインの重要性を反映しています。より高い重み付け（≥1.5×）のドメインは、全体スコアにより大きく貢献します。',

    'results.table.domain' => 'ドメイン',
    'results.table.score' => 'スコア',
    'results.table.weight' => '重み',
    'results.table.progress' => '進捗',
    'results.table.maturity' => '成熟度レベル',

    'results.improvement_actions' => '推奨される改善アクション',
    'results.domain_insights' => 'ドメインインサイト',
    'results.research_questions' => '調査すべき質問',
    'results.research_questions.description' => 'これらの質問は「不明」とマークされました - 主権態勢を改善するために、これらの領域を調査してください:',
    'results.no_research_questions' => '「不明」とマークされた質問はありません - 優れた知識カバレッジです！',

    'results.download_pdf' => 'PDFレポートをダウンロード',
    'results.take_new' => '新しい評価を実施',

    // ========================================
    // エラーメッセージ
    // ========================================
    'error.file_not_found.title' => 'リソースが見つかりません',
    'error.file_not_found.message' => '要求されたリソースがサーバー上で見つかりませんでした。',
    'error.file_not_found.what_happened' => '何が起こったか:',
    'error.file_not_found.what_happened_text' => '要求されたリソースがサーバー上で見つかりませんでした。',
    'error.file_not_found.what_to_do' => 'できること:',
    'error.file_not_found.what_to_do_text' => 'ホームページに戻ってやり直してください。問題が解決しない場合は、上記のエラーIDを添えて管理者にお問い合わせください。',
    'error.file_not_found.return_home' => 'ホームに戻る',

    'error.system_error.title' => 'システムエラー',
    'error.validation_error.title' => '検証エラー',
    'error.json_error.title' => 'JSONエラー',

    'error.error_id' => 'エラーID:',
    'error.timestamp' => 'タイムスタンプ:',

    // ========================================
    // 検証メッセージ
    // ========================================
    'validation.required' => '次に進む前に、すべての質問に答えてください。',
    'validation.unanswered' => 'このセクションには{count}個の未回答の質問があります。',
    'validation.no_answers' => '質問に答えていません。スコアは0になります。',
    'validation.confirm_continue' => '続行してもよろしいですか？',

    // ========================================
    // 通知メッセージ
    // ========================================
    'notification.progress_saved' => '進行状況が保存されました！',
    'notification.progress_restored' => '以前の進行状況が復元されました！',
    'notification.form_reset' => 'フォームがリセットされました',

    // ========================================
    // フッター / 免責事項
    // ========================================
    'footer.disclaimer' => '免責事項:',
    'footer.disclaimer_text' => 'このデジタル主権準備評価ツールは、組織が一般的な主権態勢を確認するための情報提供のみを目的として、Red Hatによって提供されています。特定の主権要件への組織のコンプライアンスを検証するために使用することはできません。いかなる規制当局によっても承認されておらず、その調査結果や推奨事項は法的助言を構成するものではありません。Red Hatは、結果またはその使用に対する法的責任または義務を負いません。個人情報は収集または保存されません。',
    'footer.copyright' => '© {year} Red Hat - Viewfinder成熟度評価ツール',

    // ========================================
    // PDF固有
    // ========================================
    'pdf.title' => 'デジタル主権準備評価結果',
    'pdf.executive_summary' => 'エグゼクティブサマリー',
    'pdf.assessment_overview' => '評価概要',
    'pdf.domain_breakdown' => 'ドメイン内訳',
    'pdf.recommendations' => '推奨事項',
    'pdf.next_steps' => '次のステップ',
];
