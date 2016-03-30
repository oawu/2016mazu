//
//  BlackListTableViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/30.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "BlackListTableViewController.h"

@interface BlackListTableViewController ()

@end

@implementation BlackListTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    
    self.list = [NSMutableArray new];
}

- (void)reloadData {
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        [self loadData:alert];
//        self.timer = [NSTimer scheduledTimerWithTimeInterval:LOAD_MESSAGE_TIMER target:self selector:@selector(loadDataByTimer) userInfo:nil repeats:YES];
    }];
}
- (void)loadData:(UIAlertController *)alert {
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.requestSerializer setCachePolicy:NSURLRequestReloadIgnoringLocalCacheData];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager GET:LOAD_MESSAGE_API_URL
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 self.isLoading = NO;
                 
                 if (![[responseObject objectForKey:@"s"] boolValue]) {
                     if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
                     return;
                 }
                 
                 int i = 0;
                 for (NSDictionary *msg in [responseObject objectForKey:@"m"])
                     if (self.maxId == 0)
                         [self.messages addObject: [[Message alloc] initWithDictionary: msg]];
                     else if ([[msg objectForKey:@"d"] integerValue] > self.maxId)
                         [self.messages insertObject: [[Message alloc] initWithDictionary: msg] atIndex: i++];
                 
                 
                 self.maxId = (int)[[self.messages firstObject].id integerValue];
                 
                 [self.tableView reloadData];
                 
                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
             failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                 self.isLoading = NO;
                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
                 //                 NSLog(@"=======>Failure!Error:%@", [[NSString alloc] initWithData:(NSData *)error.userInfo[AFNetworkingOperationFailingURLResponseDataErrorKey] encoding:NSUTF8StringEncoding]);
             }
     ];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 0;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return 0;
}

/*
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:<#@"reuseIdentifier"#> forIndexPath:indexPath];
    
    // Configure the cell...
    
    return cell;
}
*/

/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/

/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
    } else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/

/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath {
}
*/

/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
