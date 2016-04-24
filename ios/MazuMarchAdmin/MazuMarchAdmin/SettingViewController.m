//
//  SettingViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "SettingViewController.h"

@interface SettingViewController ()

@end

@implementation SettingViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    
    int s = 20;
    
    self.scrollView = [UIScrollView new];
    [self.scrollView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.view addSubview:self.scrollView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
    
    self.pathTitleLabel = [UILabel new];
    [self.pathTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pathTitleLabel setText:@"路徑顯示："];
    [self.pathTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.pathTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    
    
    self.versionTitleLabel = [UILabel new];
    [self.versionTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.versionTitleLabel setText:@"ＪＳ版本："];
    [self.versionTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.versionTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:0]];
    
    self.crontabTitleLabel = [UILabel new];
    [self.crontabTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.crontabTitleLabel setText:@"排程開關："];
    [self.crontabTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.crontabTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:0]];
    
    
    self.pointsTitleLabel = [UILabel new];
    [self.pointsTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pointsTitleLabel setText:@"路徑點數："];
    [self.pointsTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.pointsTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.crontabTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.crontabTitleLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:0]];
    
    
    self.pointsSteper = [UIStepper new];
    [self.pointsSteper setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pointsSteper setMinimumValue:0];
    [self.pointsSteper setValue:0];
    [self.pointsSteper addTarget:self action:@selector(stepperChanged:) forControlEvents:UIControlEventValueChanged];
    
    [self.scrollView addSubview:self.pointsSteper];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsSteper attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.pointsTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsSteper attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pointsTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    


    self.pathSegmentedControl = [[UISegmentedControl alloc] initWithItems:@[@"關閉", @"19下午", @"19晚間", @"20下午", @"20晚間"]];
    [self.pathSegmentedControl setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pathSegmentedControl addTarget:self action:@selector(chooseOne:) forControlEvents:UIControlEventValueChanged];
    [self.pathSegmentedControl setSelectedSegmentIndex:0];
//    [self.pathSegmentedControl setsi]

    [self.scrollView addSubview:self.pathSegmentedControl];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathSegmentedControl attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathSegmentedControl attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    self.pointsLabel = [UILabel new];
    [self.pointsLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pointsLabel setText:@""];
    [self.pointsLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview:self.pointsLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.pointsSteper attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pointsLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pointsSteper attribute:NSLayoutAttributeRight multiplier:1 constant:5]];
    
    

    
    self.versionSteper = [UIStepper new];
    [self.versionSteper setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.versionSteper setMinimumValue:0];
    [self.versionSteper setValue:0];
    [self.versionSteper addTarget:self action:@selector(versionStepperChanged:) forControlEvents:UIControlEventValueChanged];
    
    [self.scrollView addSubview:self.versionSteper];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionSteper attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionSteper attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    self.versionLabel = [UILabel new];
    [self.versionLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.versionLabel setText:@""];
    [self.versionLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview:self.versionLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.versionSteper attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.versionSteper attribute:NSLayoutAttributeRight multiplier:1 constant:5]];
    
    
    
    self.crontabSwitch = [UISwitch new];
    [self.crontabSwitch setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.crontabSwitch addTarget:self action:@selector(setState:) forControlEvents:UIControlEventValueChanged];
    
    [self.scrollView addSubview: self.crontabSwitch];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabSwitch attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.crontabTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabSwitch attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.crontabTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    

}
- (void)chooseOne:(id)sender {
    int path_id = (int)[sender selectedSegmentIndex];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%d", path_id] forKey:@"path_id"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:PUT_SETTING_API_URL
               parameters:data
                  success:^(AFHTTPRequestOperation *operation, id responseObject) {
                      [self.pathSegmentedControl setSelectedSegmentIndex:[[responseObject objectForKey:@"path_id"] integerValue]];
                      
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
                  failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
         ];
    }];
}

- (void)stepperChanged:(UIStepper*)sender {
    int version = (int)[sender value];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%d", version] forKey:@"points"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:PUT_SETTING_API_URL
               parameters:data
                  success:^(AFHTTPRequestOperation *operation, id responseObject) {
                      [self.pointsSteper setValue:[[responseObject objectForKey:@"points"] integerValue]];
                      [self.pointsLabel setText:[NSString stringWithFormat:@"%@", [responseObject objectForKey:@"points"]]];
                      
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
                  failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
         ];
    }];
}


- (void)versionStepperChanged:(UIStepper*)sender {
    int version = (int)[sender value];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%d", version] forKey:@"version"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:PUT_SETTING_API_URL
               parameters:data
                  success:^(AFHTTPRequestOperation *operation, id responseObject) {
                      [self.versionSteper setValue:[[responseObject objectForKey:@"version"] integerValue]];
                      [self.versionLabel setText:[NSString stringWithFormat:@"%@", [responseObject objectForKey:@"version"]]];

                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
                  failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
         ];
    }];
}

-(void)setState:(id)sender {
    BOOL is_crontab = [sender isOn];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"更新中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        NSMutableDictionary *data = [NSMutableDictionary new];
        [data setValue:[NSString stringWithFormat:@"%@", is_crontab ? @"1" : @"0"] forKey:@"is_crontab"];
        [data setValue:@"put" forKey:@"_method"];
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager POST:PUT_SETTING_API_URL
               parameters:data
                  success:^(AFHTTPRequestOperation *operation, id responseObject) {
                      [self.crontabSwitch setOn:[[responseObject objectForKey:@"is_crontab"] boolValue] animated:NO];
                      
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
                  failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                      [alert dismissViewControllerAnimated:YES completion:nil];
                  }
         ];
    }];
}


- (void)setHidden {
    [self.pathTitleLabel setHidden:YES];
    [self.versionTitleLabel setHidden:YES];
    [self.versionLabel setHidden:YES];
    [self.crontabTitleLabel setHidden:YES];
    [self.pathSegmentedControl setHidden:YES];
    [self.versionSteper setHidden:YES];
    [self.crontabSwitch setHidden:YES];
    [self.pointsTitleLabel setHidden:YES];
    [self.pointsLabel setHidden:YES];
    [self.pointsSteper setHidden:YES];
}


- (void)setShow:(NSDictionary *) data {
    [self.pathTitleLabel setHidden:NO];
    [self.versionTitleLabel setHidden:NO];
    [self.versionLabel setHidden:NO];
    [self.crontabTitleLabel setHidden:NO];
    [self.pathSegmentedControl setHidden:NO];
    [self.versionSteper setHidden:NO];
    [self.crontabSwitch setHidden:NO];
    [self.pointsTitleLabel setHidden:NO];
    [self.pointsLabel setHidden:NO];
    [self.pointsSteper setHidden:NO];
    
    
    [self.pathSegmentedControl setSelectedSegmentIndex:[[data objectForKey:@"path_id"] integerValue]];
    
    [self.versionSteper setValue:[[data objectForKey:@"version"] integerValue]];
    [self.versionLabel setText:[NSString stringWithFormat:@"%@", [data objectForKey:@"version"]]];
        
    [self.crontabSwitch setOn:[[data objectForKey:@"is_crontab"] boolValue] animated:NO];
    
    [self.pointsSteper setValue:[[data objectForKey:@"points"] integerValue]];
    [self.pointsLabel setText:[NSString stringWithFormat:@"%@", [data objectForKey:@"points"]]];
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self setHidden];
    
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        
        AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
        [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
        [httpManager GET:GET_SETTING_API_URL
              parameters:nil
                 success:^(AFHTTPRequestOperation *operation, id responseObject) {
                     [self setShow: responseObject];
                     [alert dismissViewControllerAnimated:YES completion:nil];
                 }
                 failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                     
                     [alert dismissViewControllerAnimated:YES completion:^{
                         UIAlertController *backAlert = [UIAlertController
                                                         alertControllerWithTitle:@"目前沒有任何資料！"
                                                         message:nil
                                                         preferredStyle:UIAlertControllerStyleAlert];
                         
                         [backAlert addAction:[UIAlertAction
                                               actionWithTitle:@"確定"
                                               style:UIAlertActionStyleDefault
                                               handler:^(UIAlertAction * action) {
                                                   [self.navigationController popViewControllerAnimated:YES];
                                               }]];
                         
                         [self.parentViewController presentViewController:backAlert animated:YES completion:nil];
                     }];
                 }
         ];
    }];
    
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
